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
        $error = 'Form session expired. Please refresh the page and try again.';
    } else {
        $name = trim($_POST['customer_name'] ?? '');
        $phone = trim($_POST['customer_phone'] ?? '');
        $address = trim($_POST['customer_address'] ?? '');
        $notes = trim($_POST['customer_notes'] ?? '');
        $cartJson = $_POST['cart_data'] ?? '[]';
        $cartItems = json_decode($cartJson, true);

        if (empty($name) || empty($phone) || empty($address)) {
            $error = 'Please fill in your Name, WhatsApp Number, and Delivery Address.';
        } elseif (empty($cartItems) || !is_array($cartItems)) {
            $error = 'Your shopping cart is empty. Please select products first.';
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
                    'name'     => $item['name'] ?? 'Product',
                    'price'    => $price,
                    'qty'      => $qty,
                    'subtotal' => $subtotal
                ];
            }

            // Compose formatted WhatsApp message
            $waAdminNumber = preg_replace('/[^0-9]/', '', $settings['whatsapp_number']);
            $currentDateStr = format_date(date('Y-m-d H:i:s'));

            $waMessage = "Hello Admin *" . $settings['store_name'] . "*,\n";
            $waMessage .= "I would like to confirm a new order:\n\n";
            $waMessage .= "📋 *ORDER NUMBER:* `" . $orderNumber . "`\n";
            $waMessage .= "📅 *DATE:* " . $currentDateStr . "\n\n";
            $waMessage .= "👤 *CUSTOMER DETAILS:*\n";
            $waMessage .= "• Name: *" . $name . "*\n";
            $waMessage .= "• WhatsApp: " . $phone . "\n";
            $waMessage .= "• Address: " . $address . "\n";
            if (!empty($notes)) {
                $waMessage .= "• Notes: " . $notes . "\n";
            }
            $waMessage .= "\n🛒 *ORDER ITEMS:*\n";

            $index = 1;
            foreach ($processedItems as $pItem) {
                $waMessage .= $index . ". *" . $pItem['name'] . "* (" . $pItem['qty'] . "x @ " . format_rupiah($pItem['price']) . ") = *" . format_rupiah($pItem['subtotal']) . "*\n";
                $index++;
            }

            $waMessage .= "\n💰 *TOTAL AMOUNT:* *" . format_rupiah($totalAmount) . "*\n\n";
            $waMessage .= "Please provide shipping cost & payment details. Thank you! 🙏";

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

$active_nav = 'demo';
$page_title = 'Checkout Order - ' . $settings['store_name'];
require_once __DIR__ . '/includes/header.php';
?>

<div class="bg-white border-b border-slate-200/80 py-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl sm:text-3xl font-semibold text-slate-900 tracking-tight">WhatsApp Checkout</h1>
        <p class="text-xs text-slate-500 mt-1">Complete your delivery details to generate an instant WhatsApp order summary.</p>
    </div>
</div>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{
    cartDataInput: JSON.stringify($store.cart.items),
    syncCart() {
        this.cartDataInput = JSON.stringify($store.cart.items);
    }
}">

    <!-- If cart is empty -->
    <div x-cloak x-show="$store.cart.items.length === 0">
        <?= ui_empty_state(
            'Your Shopping Cart is Empty',
            'Please select products from the catalog before proceeding to checkout.',
            [
                'icon'       => 'shopping-cart',
                'buttonText' => 'Back to Product Catalog',
                'buttonHref' => base_url('demo.php'),
                'buttonIcon' => 'arrow-left',
            ]
        ) ?>
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
                    <h2 class="text-lg font-semibold text-slate-900 tracking-tight flex items-center gap-2">
                        <i class="ph ph-user-check text-xl text-brand-600"></i>
                        <span>Customer &amp; Delivery Information</span>
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">Please ensure accurate information for prompt delivery.</p>
                </div>

                <div class="space-y-4">
                    <?= ui_input('customer_name', [
                        'label'       => 'Full Name',
                        'placeholder' => 'e.g. John Doe',
                        'value'       => $_POST['customer_name'] ?? '',
                        'required'    => true,
                        'icon'        => 'user',
                    ]) ?>

                    <?= ui_input('customer_phone', [
                        'label'       => 'Active WhatsApp / Phone Number',
                        'type'        => 'tel',
                        'placeholder' => 'e.g. +1 (555) 234-5678',
                        'value'       => $_POST['customer_phone'] ?? '',
                        'required'    => true,
                        'helper'      => 'Used by admin for order confirmation & tracking updates.',
                        'icon'        => 'phone',
                    ]) ?>

                    <?= ui_textarea('customer_address', [
                        'label'       => 'Full Delivery Address',
                        'rows'        => 3,
                        'placeholder' => 'e.g. 742 Evergreen Terrace, Springfield, OR 97477',
                        'value'       => $_POST['customer_address'] ?? '',
                        'required'    => true,
                    ]) ?>

                    <?= ui_input('customer_notes', [
                        'label'       => 'Order Notes (Optional)',
                        'placeholder' => 'e.g. Leave with building security / extra protective packaging',
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
                        <span class="font-semibold text-slate-800 block">How Does WhatsApp Checkout Work?</span>
                        <p class="text-slate-500 mt-0.5 leading-relaxed">After clicking the button, you will be automatically redirected to the official WhatsApp Admin chat with a pre-formatted order summary.</p>
                    </div>
                </div>

            </div>

            <!-- Right: Order Items Summary & Submit CTA -->
            <div class="lg:col-span-5 space-y-6 sticky top-28">
                <div class="bg-white rounded-card border border-slate-200/80 p-6 sm:p-7 space-y-6">
                    
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <h3 class="font-semibold text-base text-slate-900 tracking-tight">Order Summary</h3>
                        <a href="<?= base_url('cart.php') ?>" class="text-xs font-semibold text-brand-600 hover:underline">Edit Cart</a>
                    </div>

                    <!-- Mini items list -->
                    <div class="max-h-60 overflow-y-auto divide-y divide-slate-100 pr-1">
                        <template x-for="item in $store.cart.items" :key="item.id">
                            <div class="py-3 flex items-center justify-between gap-3 text-xs">
                                <div class="flex items-center gap-3 min-w-0">
                                    <img :src="item.image" :alt="item.name" class="w-10 h-10 rounded-btn object-cover border border-slate-200 flex-shrink-0 bg-slate-50">
                                    <div class="min-w-0">
                                        <p class="font-semibold text-slate-800 truncate" x-text="item.name"></p>
                                        <p class="text-slate-400 text-[11px]" x-text="item.qty + 'x @ ' + $store.cart.formatRupiah(item.price)"></p>
                                    </div>
                                </div>
                                <span class="font-semibold text-slate-800 flex-shrink-0" x-text="$store.cart.formatRupiah(item.price * item.qty)"></span>
                            </div>
                        </template>
                    </div>

                    <!-- Totals -->
                    <div class="pt-4 border-t border-slate-100 space-y-2.5 text-xs">
                        <div class="flex justify-between text-slate-500">
                            <span>Total Items</span>
                            <span class="font-semibold text-slate-800" x-text="$store.cart.count + ' pcs'"></span>
                        </div>
                        <div class="flex justify-between text-slate-500">
                            <span>Subtotal</span>
                            <span class="font-semibold text-slate-800" x-text="$store.cart.formatRupiah($store.cart.subtotal)"></span>
                        </div>
                        <div class="flex justify-between text-slate-500">
                            <span>Shipping Fee</span>
                            <span class="text-brand-600 font-semibold">Calculated by Admin on WA</span>
                        </div>

                        <div class="pt-3 border-t border-slate-200/80 flex justify-between items-baseline">
                            <span class="text-sm font-semibold text-slate-900">Estimated Total</span>
                            <span class="text-xl font-semibold text-brand-600 tracking-tight" x-text="$store.cart.formatRupiah($store.cart.subtotal)"></span>
                        </div>
                    </div>

                    <!-- Submit Button (Apple Tactile) -->
                    <?= ui_button('Place Order via WhatsApp', [
                        'variant' => 'primary',
                        'type'    => 'submit',
                        'size'    => 'lg',
                        'icon'    => 'whatsapp-logo',
                        'class'   => 'w-full py-4 text-sm',
                    ]) ?>

                    <p class="text-[11px] text-slate-400 text-center">
                        Support WhatsApp: <strong><?= sanitize($settings['whatsapp_number']) ?></strong>
                    </p>

                </div>
            </div>

        </form>

    </div>

</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
