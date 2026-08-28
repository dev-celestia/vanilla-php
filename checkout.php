<?php
/**
 * Checkout & WhatsApp Order Processor
 */
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/helpers/format.php';
require_once __DIR__ . '/helpers/csrf.php';

$settings = get_settings();
$error = null;

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Sesi form telah kedaluwarsa. Silakan muat ulang halaman dan coba lagi.';
    } else {
        $name = trim($_POST['customer_name'] ?? '');
        $phone = trim($_POST['customer_phone'] ?? '');
        $address = trim($_POST['customer_address'] ?? '');
        $notes = trim($_POST['customer_notes'] ?? '');
        $cartJson = $_POST['cart_data'] ?? '[]';
        $cartItems = json_decode($cartJson, true);

        if (empty($name) || empty($phone) || empty($address)) {
            $error = 'Mohon lengkapi Nama, Nomor WhatsApp, dan Alamat Pengiriman.';
        } elseif (empty($cartItems) || !is_array($cartItems)) {
            $error = 'Keranjang belanja Anda kosong. Silakan pilih produk terlebih dahulu.';
        } else {
            $db = getDB();
            $orderNumber = generate_order_number();
            $totalAmount = 0;
            $processedItems = [];

            // Calculate total and prepare items
            foreach ($cartItems as $item) {
                $qty = max(1, (int)($item['qty'] ?? 1));
                $price = (float)($item['price'] ?? 0);
                $subtotal = $price * $qty;
                $totalAmount += $subtotal;

                $processedItems[] = [
                    'id'       => $item['id'] ?? null,
                    'name'     => $item['name'] ?? 'Produk',
                    'price'    => $price,
                    'qty'      => $qty,
                    'subtotal' => $subtotal
                ];
            }

            // Compose formatted WhatsApp message
            $waAdminNumber = preg_replace('/[^0-9]/', '', $settings['whatsapp_number']);
            $currentDateStr = format_date(date('Y-m-d H:i:s'));

            $waMessage = "Halo Admin *" . $settings['store_name'] . "*,\n";
            $waMessage .= "Saya ingin konfirmasi pemesanan baru:\n\n";
            $waMessage .= "📋 *KODE PESANAN:* `" . $orderNumber . "`\n";
            $waMessage .= "📅 *WAKTU:* " . $currentDateStr . "\n\n";
            $waMessage .= "👤 *DATA PEMBELI:*\n";
            $waMessage .= "• Nama: *" . $name . "*\n";
            $waMessage .= "• No. WA: " . $phone . "\n";
            $waMessage .= "• Alamat: " . $address . "\n";
            if (!empty($notes)) {
                $waMessage .= "• Catatan: " . $notes . "\n";
            }
            $waMessage .= "\n🛒 *RINCIAN BARANG:*\n";

            $index = 1;
            foreach ($processedItems as $pItem) {
                $waMessage .= $index . ". *" . $pItem['name'] . "* (" . $pItem['qty'] . "x @ " . format_rupiah($pItem['price']) . ") = *" . format_rupiah($pItem['subtotal']) . "*\n";
                $index++;
            }

            $waMessage .= "\n💰 *TOTAL BELANJA:* *" . format_rupiah($totalAmount) . "*\n\n";
            $waMessage .= "Mohon informasi ongkir & nomor rekening pembayarannya ya. Terima kasih! 🙏";

            $waUrl = "https://api.whatsapp.com/send?phone=" . $waAdminNumber . "&text=" . urlencode($waMessage);

            // Save to Database if DB connection exists
            if ($db) {
                try {
                    $db->beginTransaction();

                    $orderStmt = $db->prepare("INSERT INTO orders (order_number, customer_name, customer_phone, customer_address, customer_notes, total_amount, status, whatsapp_url) 
                                               VALUES (:order_number, :customer_name, :customer_phone, :customer_address, :customer_notes, :total_amount, 'pending', :whatsapp_url)");
                    $orderStmt->execute([
                        ':order_number'      => $orderNumber,
                        ':customer_name'     => $name,
                        ':customer_phone'    => $phone,
                        ':customer_address'  => $address,
                        ':customer_notes'    => $notes,
                        ':total_amount'      => $totalAmount,
                        ':whatsapp_url'      => $waUrl
                    ]);

                    $orderId = $db->lastInsertId();

                    $itemStmt = $db->prepare("INSERT INTO order_items (order_id, product_id, product_name, price, quantity, subtotal) 
                                              VALUES (:order_id, :product_id, :product_name, :price, :quantity, :subtotal)");

                    foreach ($processedItems as $pItem) {
                        $itemStmt->execute([
                            ':order_id'     => $orderId,
                            ':product_id'   => $pItem['id'],
                            ':product_name' => $pItem['name'],
                            ':price'        => $pItem['price'],
                            ':quantity'     => $pItem['qty'],
                            ':subtotal'     => $pItem['subtotal']
                        ]);

                        // Reduce stock if product id exists
                        if (!empty($pItem['id'])) {
                            $stockStmt = $db->prepare("UPDATE products SET stock = GREATEST(0, stock - :qty) WHERE id = :id");
                            $stockStmt->execute([':qty' => $pItem['qty'], ':id' => $pItem['id']]);
                        }
                    }

                    $db->commit();
                } catch (Exception $e) {
                    if ($db->inTransaction()) {
                        $db->rollBack();
                    }
                    error_log("Order save error: " . $e->getMessage());
                }
            }

            // Redirect to Success Page
            $_SESSION['latest_order'] = [
                'order_number' => $orderNumber,
                'name'         => $name,
                'phone'        => $phone,
                'address'      => $address,
                'total'        => $totalAmount,
                'items'        => $processedItems,
                'wa_url'       => $waUrl
            ];

            header('Location: ' . base_url('order-success.php?order=' . urlencode($orderNumber)));
            exit;
        }
    }
}

$page_title = 'Checkout Pesanan - ' . $settings['store_name'];
require_once __DIR__ . '/includes/header.php';
?>

<div class="bg-white border-b border-slate-200/80 py-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Formulir Checkout WhatsApp</h1>
        <p class="text-xs text-slate-500 mt-1">Lengkapi data pengiriman untuk membuat rincian pesanan instan ke WhatsApp.</p>
    </div>
</div>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{
    cartDataInput: JSON.stringify($store.cart.items),
    syncCart() {
        this.cartDataInput = JSON.stringify($store.cart.items);
    }
}">

    <!-- If cart is empty -->
    <div x-cloak x-show="$store.cart.items.length === 0" class="bg-white rounded-card border border-slate-200/80 p-16 text-center max-w-xl mx-auto my-8">
        <div class="w-20 h-20 rounded-card bg-slate-100 border border-slate-200/80 text-slate-400 flex items-center justify-center mx-auto mb-5">
            <i class="ph ph-shopping-cart text-5xl"></i>
        </div>
        <h2 class="text-xl font-bold text-slate-900 mb-2 tracking-tight">Keranjang Belanja Anda Kosong</h2>
        <p class="text-xs text-slate-500 mb-8 max-w-sm mx-auto leading-relaxed">
            Silakan pilih produk yang Anda minati di katalog sebelum melanjutkan proses checkout.
        </p>
        <?= ui_button('Kembali ke Katalog Produk', [
            'variant' => 'primary',
            'size'    => 'md',
            'href'    => base_url(),
            'icon'    => 'arrow-left',
        ]) ?>
    </div>

    <!-- Active Checkout Form -->
    <div x-cloak x-show="$store.cart.items.length > 0">
        
        <?php if (!empty($error)): ?>
            <?= ui_alert(sanitize($error), 'danger', ['class' => 'mb-6', 'dismissible' => true]) ?>
        <?php endif; ?>

        <form action="<?= base_url('checkout.php') ?>" method="POST" @submit="syncCart()" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <?= csrf_field() ?>
            
            <!-- Hidden JSON Cart Data -->
            <input type="hidden" name="cart_data" :value="JSON.stringify($store.cart.items)">

            <!-- Left: Customer Details Form (Zero Shadow, Crisp Hairline Border) -->
            <div class="lg:col-span-7 bg-white rounded-card border border-slate-200/80 p-6 sm:p-8 space-y-6">
                
                <div class="border-b border-slate-100 pb-4">
                    <h2 class="text-lg font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                        <i class="ph ph-user-check text-xl text-brand-600"></i>
                        <span>Informasi Pemesan & Pengiriman</span>
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">Pastikan data yang dimasukkan benar agar pengiriman lancar.</p>
                </div>

                <div class="space-y-4">
                    <?= ui_input('customer_name', [
                        'label'       => 'Nama Lengkap',
                        'placeholder' => 'Contoh: Budi Pratama',
                        'value'       => $_POST['customer_name'] ?? '',
                        'required'    => true,
                        'icon'        => 'user',
                    ]) ?>

                    <?= ui_input('customer_phone', [
                        'label'       => 'Nomor WhatsApp Aktif',
                        'type'        => 'tel',
                        'placeholder' => 'Contoh: 081234567890',
                        'value'       => $_POST['customer_phone'] ?? '',
                        'required'    => true,
                        'helper'      => 'Nomor ini akan digunakan admin untuk konfirmasi resi & pengiriman.',
                        'icon'        => 'phone',
                    ]) ?>

                    <?= ui_textarea('customer_address', [
                        'label'       => 'Alamat Lengkap Pengiriman',
                        'rows'        => 3,
                        'placeholder' => 'Contoh: Jl. Melati No. 12, RT 03/RW 02, Kel. Menteng, Kec. Menteng, Jakarta Pusat 10310',
                        'value'       => $_POST['customer_address'] ?? '',
                        'required'    => true,
                    ]) ?>

                    <?= ui_input('customer_notes', [
                        'label'       => 'Catatan Pesanan (Opsional)',
                        'placeholder' => 'Contoh: Titipkan di satpam jika tidak ada orang / Packing tambahan',
                        'value'       => $_POST['customer_notes'] ?? '',
                        'icon'        => 'pencil-simple',
                    ]) ?>
                </div>

                <!-- Trust banner inside form -->
                <div class="p-4 rounded-card bg-brand-50/50 border border-brand-200/70 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-btn bg-brand-100 text-brand-700 flex items-center justify-center flex-shrink-0">
                        <i class="ph ph-whatsapp-logo text-xl"></i>
                    </div>
                    <div class="text-xs">
                        <span class="font-bold text-slate-800 block">Bagaimana Alur WhatsApp Checkout Bekerja?</span>
                        <p class="text-slate-500 mt-0.5 leading-relaxed">Setelah klik tombol di samping, Anda akan otomatis diarahkan ke chat WhatsApp Admin resmi dengan template format rincian pesanan yang rapi.</p>
                    </div>
                </div>

            </div>

            <!-- Right: Order Items Summary & Submit CTA -->
            <div class="lg:col-span-5 space-y-6 sticky top-28">
                <div class="bg-white rounded-card border border-slate-200/80 p-6 sm:p-7 space-y-6">
                    
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <h3 class="font-extrabold text-base text-slate-900 tracking-tight">Rincian Pesanan</h3>
                        <a href="<?= base_url('cart.php') ?>" class="text-xs font-bold text-brand-600 hover:underline">Ubah Keranjang</a>
                    </div>

                    <!-- Mini items list -->
                    <div class="max-h-60 overflow-y-auto divide-y divide-slate-100 pr-1">
                        <template x-for="item in $store.cart.items" :key="item.id">
                            <div class="py-3 flex items-center justify-between gap-3 text-xs">
                                <div class="flex items-center gap-3 min-w-0">
                                    <img :src="item.image" :alt="item.name" class="w-10 h-10 rounded-btn object-cover border border-slate-200 flex-shrink-0 bg-slate-50">
                                    <div class="min-w-0">
                                        <p class="font-bold text-slate-800 truncate" x-text="item.name"></p>
                                        <p class="text-slate-400 text-[11px]" x-text="item.qty + 'x @ ' + $store.cart.formatRupiah(item.price)"></p>
                                    </div>
                                </div>
                                <span class="font-extrabold text-slate-800 flex-shrink-0" x-text="$store.cart.formatRupiah(item.price * item.qty)"></span>
                            </div>
                        </template>
                    </div>

                    <!-- Totals -->
                    <div class="pt-4 border-t border-slate-100 space-y-2.5 text-xs">
                        <div class="flex justify-between text-slate-500">
                            <span>Total Item</span>
                            <span class="font-bold text-slate-800" x-text="$store.cart.count + ' pcs'"></span>
                        </div>
                        <div class="flex justify-between text-slate-500">
                            <span>Subtotal Barang</span>
                            <span class="font-bold text-slate-800" x-text="$store.cart.formatRupiah($store.cart.subtotal)"></span>
                        </div>
                        <div class="flex justify-between text-slate-500">
                            <span>Ongkos Kirim</span>
                            <span class="text-brand-600 font-bold">Dihitung Admin via WA</span>
                        </div>

                        <div class="pt-3 border-t border-slate-200/80 flex justify-between items-baseline">
                            <span class="text-sm font-extrabold text-slate-900">Total Perkiraan</span>
                            <span class="text-xl font-black text-brand-600 tracking-tight" x-text="$store.cart.formatRupiah($store.cart.subtotal)"></span>
                        </div>
                    </div>

                    <!-- Submit Button (Apple Tactile) -->
                    <button 
                        type="submit" 
                        class="w-full py-4 px-6 rounded-btn bg-brand-600 hover:bg-brand-700 text-white font-extrabold text-sm border border-brand-500/20 transition apple-tap flex items-center justify-center gap-2 group">
                        <i class="ph ph-whatsapp-logo text-xl group-hover:scale-110 transition-transform"></i>
                        <span>Proses Pesanan via WhatsApp</span>
                    </button>

                    <p class="text-[11px] text-slate-400 text-center">
                        Nomor WhatsApp CS: <strong><?= sanitize($settings['whatsapp_number']) ?></strong>
                    </p>

                </div>
            </div>

        </form>

    </div>

</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
