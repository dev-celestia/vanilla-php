<?php
/**
 * Order Success & WhatsApp Redirection Confirmation
 */
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/helpers/format.php';

$orderNumber = trim($_GET['order'] ?? '');
$orderData = $_SESSION['latest_order'] ?? null;
$settings = get_settings();

$db = getDB();
$dbOrder = null;
$dbItems = [];

if ($db && !empty($orderNumber)) {
    try {
        $stmt = $db->prepare("SELECT * FROM orders WHERE order_number = :order_number LIMIT 1");
        $stmt->execute([':order_number' => $orderNumber]);
        $dbOrder = $stmt->fetch();

        if ($dbOrder) {
            $itemStmt = $db->prepare("SELECT * FROM order_items WHERE order_id = :order_id");
            $itemStmt->execute([':order_id' => $dbOrder['id']]);
            $dbItems = $itemStmt->fetchAll();
        }
    } catch (PDOException $e) {
        error_log("Order fetch error: " . $e->getMessage());
    }
}

// Fallback to session data if DB was not reachable
$displayOrder = $dbOrder ?: $orderData;
$waUrl = $dbOrder['whatsapp_url'] ?? ($orderData['wa_url'] ?? 'https://wa.me/' . preg_replace('/[^0-9]/', '', $settings['whatsapp_number']));
$page_title = 'Pesanan Berhasil - ' . $settings['store_name'];

require_once __DIR__ . '/includes/header.php';
?>

<section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center" x-init="$store.cart.clearCart()">
    
    <div class="bg-white rounded-card border border-slate-200/80 p-8 sm:p-12 text-left relative overflow-hidden">
        
        <!-- Header badge -->
        <div class="text-center pb-8 border-b border-slate-100">
            <?= ui_icon_box('check-circle', 'brand', ['size' => 'lg', 'class' => 'mx-auto mb-4']) ?>
            <?= ui_badge('Pesanan Berhasil Dicatat!', 'brand', ['dot' => true, 'class' => 'mb-2']) ?>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                Terima Kasih atas Pesanan Anda
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-2 max-w-md mx-auto leading-relaxed">
                Silakan lanjutkan langkah terakhir dengan mengirimkan format chat ke WhatsApp Admin kami.
            </p>
        </div>

        <!-- Order Number & Info Card -->
        <div class="my-6 p-5 rounded-card bg-slate-50 border border-slate-200/80 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div>
                <span class="text-xs text-slate-400 font-medium block">Nomor Pesanan / Invoice:</span>
                <span class="text-base sm:text-lg font-mono font-extrabold text-slate-900 tracking-wider">
                    <?= sanitize($displayOrder['order_number'] ?? $orderNumber) ?>
                </span>
            </div>
            <div class="text-right">
                <span class="text-xs text-slate-400 font-medium block">Total Belanja:</span>
                <span class="text-base sm:text-lg font-black text-brand-600 tracking-tight">
                    <?= format_rupiah($displayOrder['total_amount'] ?? ($displayOrder['total'] ?? 0)) ?>
                </span>
            </div>
        </div>

        <!-- Customer & Item Recap -->
        <div class="space-y-4 text-xs text-slate-600 border-b border-slate-100 pb-6">
            <h3 class="font-extrabold text-slate-900 uppercase text-[11px] tracking-wider">Data Pengiriman:</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 bg-white p-4 rounded-card border border-slate-200/80">
                <div>
                    <span class="text-slate-400 block">Nama Penerima:</span>
                    <span class="font-bold text-slate-800"><?= sanitize($displayOrder['customer_name'] ?? ($displayOrder['name'] ?? '-')) ?></span>
                </div>
                <div>
                    <span class="text-slate-400 block">No. WhatsApp:</span>
                    <span class="font-bold text-slate-800"><?= sanitize($displayOrder['customer_phone'] ?? ($displayOrder['phone'] ?? '-')) ?></span>
                </div>
                <div class="sm:col-span-2">
                    <span class="text-slate-400 block">Alamat Tujuan:</span>
                    <span class="font-bold text-slate-800"><?= sanitize($displayOrder['customer_address'] ?? ($displayOrder['address'] ?? '-')) ?></span>
                </div>
            </div>
        </div>

        <!-- Action WA Button -->
        <div class="pt-6 space-y-4">
            <?= ui_button('Buka & Kirim Pesanan ke WhatsApp Sekarang', [
                'variant' => 'primary',
                'size'    => 'lg',
                'href'    => $waUrl,
                'target'  => '_blank',
                'icon'    => 'whatsapp-logo',
                'class'   => 'w-full',
            ]) ?>

            <div class="flex items-center justify-center gap-4 pt-2">
                <a href="<?= base_url('demo.php') ?>" class="inline-flex items-center gap-1.5 text-xs font-bold text-brand-600 hover:text-brand-700">
                    <i class="ph ph-shopping-bag text-xs"></i>
                    <span>Katalog Demo</span>
                </a>
                <span class="text-slate-300">•</span>
                <a href="<?= base_url() ?>" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-slate-900">
                    <i class="ph ph-house text-xs"></i>
                    <span>Halaman Beranda</span>
                </a>
            </div>
        </div>

    </div>

</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
