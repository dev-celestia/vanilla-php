<?php
/**
 * Admin WhatsApp Orders Management
 */
$active_menu = 'orders';
$page_title = 'WhatsApp Order History';
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../helpers/format.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/csrf.php';

$db = getDB();
$error = null;

// Handle Status Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    if (verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $orderId = (int)($_POST['order_id'] ?? 0);
        $newStatus = $_POST['status'] ?? 'pending';
        $allowed = ['pending', 'processing', 'completed', 'cancelled'];

        if ($orderId > 0 && in_array($newStatus, $allowed) && $db) {
            try {
                $stmt = $db->prepare("UPDATE orders SET status = :status WHERE id = :id");
                $stmt->execute([':status' => $newStatus, ':id' => $orderId]);
                set_flash('success', 'Order status #' . $orderId . ' updated to ' . strtoupper($newStatus));
                header('Location: ' . base_url('admin/orders.php'));
                exit;
            } catch (PDOException $e) {
                set_flash('error', 'Failed to update order status: ' . $e->getMessage());
            }
        }
    }
}

// Search & Filter
$search = trim($_GET['search'] ?? '');
$statusFilter = $_GET['status'] ?? 'all';

$orders = [];
if ($db) {
    $sql = "SELECT * FROM orders WHERE 1=1";
    $params = [];

    if (!empty($search)) {
        $sql .= " AND (order_number LIKE :search OR customer_name LIKE :search OR customer_phone LIKE :search)";
        $params[':search'] = '%' . $search . '%';
    }

    if ($statusFilter !== 'all') {
        $sql .= " AND status = :status";
        $params[':status'] = $statusFilter;
    }

    $sql .= " ORDER BY created_at DESC";
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $orders = $stmt->fetchAll();

    // Fetch items for each order
    foreach ($orders as &$order) {
        $itemStmt = $db->prepare("SELECT * FROM order_items WHERE order_id = :order_id");
        $itemStmt->execute([':order_id' => $order['id']]);
        $order['items'] = $itemStmt->fetchAll();
    }
}

require_once __DIR__ . '/includes/admin_header.php';
?>

<div class="space-y-6" x-data="{
    detailModalOpen: false,
    selectedOrder: null,

    openDetail(order) {
        this.selectedOrder = order;
        this.detailModalOpen = true;
    },

    formatCurrency(val) {
        return '$' + Number(val || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }
}">

    <!-- Filter & Header (Zero Shadow, Crisp Border, Token Radius) -->
    <div class="bg-white p-6 rounded-card border border-slate-200/80 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-base font-extrabold text-slate-900 tracking-tight">Incoming WhatsApp Orders (<?= count($orders) ?>)</h2>
                <p class="text-xs text-slate-400">Track and update order fulfillment statuses processed via WhatsApp</p>
            </div>
        </div>

        <form method="GET" action="<?= base_url('admin/orders.php') ?>" class="grid grid-cols-1 sm:grid-cols-12 gap-3 pt-3 border-t border-slate-100">
            <div class="sm:col-span-6 relative">
                <input 
                    type="text" 
                    name="search" 
                    value="<?= sanitize($search) ?>" 
                    placeholder="Search order number, customer name, or phone..." 
                    class="w-full pl-9 pr-4 py-2.5 text-xs rounded-input bg-slate-50 border border-slate-200/90 focus:bg-white focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20"
                >
                <i class="ph ph-magnifying-glass text-slate-400 absolute left-3 top-3 text-sm"></i>
            </div>

            <div class="sm:col-span-4">
                <select name="status" class="w-full px-3 py-2.5 text-xs rounded-input bg-slate-50 border border-slate-200/90 focus:bg-white focus:border-brand-500 focus:outline-none cursor-pointer">
                    <option value="all" <?= $statusFilter === 'all' ? 'selected' : '' ?>>All Order Statuses</option>
                    <option value="pending" <?= $statusFilter === 'pending' ? 'selected' : '' ?>>Pending (Awaiting Action)</option>
                    <option value="processing" <?= $statusFilter === 'processing' ? 'selected' : '' ?>>Processing (In Fulfillment)</option>
                    <option value="completed" <?= $statusFilter === 'completed' ? 'selected' : '' ?>>Completed (Delivered)</option>
                    <option value="cancelled" <?= $statusFilter === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                </select>
            </div>

            <div class="sm:col-span-2 flex gap-2">
                <button type="submit" class="w-full py-2.5 px-4 rounded-btn bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition apple-tap">
                    Filter
                </button>
                <?php if (!empty($search) || $statusFilter !== 'all'): ?>
                    <a href="<?= base_url('admin/orders.php') ?>" class="p-2.5 rounded-btn bg-slate-100 text-slate-600 hover:bg-slate-200 border border-slate-200/80 text-xs font-bold transition apple-tap" title="Reset Filter">
                        <i class="ph ph-arrows-counter-clockwise text-sm"></i>
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Orders Table (Zero Shadow, Crisp Border) -->
    <div class="bg-white rounded-card border border-slate-200/80 overflow-hidden">
        <?php if (empty($orders)): ?>
            <?= ui_empty_state('No Orders Found', 'There are no customer orders matching your criteria.', ['icon' => 'tray']) ?>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Code &amp; Date</th>
                            <th class="px-6 py-4">Customer Details</th>
                            <th class="px-6 py-4">Items</th>
                            <th class="px-6 py-4">Total Amount</th>
                            <th class="px-6 py-4">Status &amp; Update</th>
                            <th class="px-6 py-4 text-right">Details</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php foreach ($orders as $ord): ?>
                            <?php 
                                $customerPhoneClean = preg_replace('/[^0-9]/', '', $ord['customer_phone']);
                                $custWaLink = "https://wa.me/{$customerPhoneClean}";
                            ?>
                            <tr class="hover:bg-slate-50/70 transition">
                                <!-- Order Code & Date -->
                                <td class="px-6 py-4">
                                    <span class="font-mono font-extrabold text-slate-900 block"><?= sanitize($ord['order_number']) ?></span>
                                    <span class="text-[11px] text-slate-400"><?= format_date($ord['created_at']) ?></span>
                                </td>

                                <!-- Customer Details -->
                                <td class="px-6 py-4">
                                    <span class="font-bold text-slate-900 block"><?= sanitize($ord['customer_name']) ?></span>
                                    <a href="<?= $custWaLink ?>" target="_blank" class="inline-flex items-center gap-1 text-[11px] text-brand-600 font-semibold hover:underline mt-0.5">
                                        <i class="ph ph-whatsapp-logo text-xs"></i>
                                        <span><?= sanitize($ord['customer_phone']) ?></span>
                                    </a>
                                </td>

                                <!-- Items Count -->
                                <td class="px-6 py-4 font-semibold text-slate-700">
                                    <?= count($ord['items'] ?? []) ?> items
                                </td>

                                <!-- Total Amount -->
                                <td class="px-6 py-4 font-extrabold text-brand-600 text-sm">
                                    <?= format_rupiah($ord['total_amount']) ?>
                                </td>

                                <!-- Status & Quick Status Update -->
                                <td class="px-6 py-4">
                                    <form action="<?= base_url('admin/orders.php') ?>" method="POST" class="flex items-center gap-2">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="action" value="update_status">
                                        <input type="hidden" name="order_id" value="<?= $ord['id'] ?>">

                                        <select 
                                            name="status" 
                                            onchange="this.form.submit()" 
                                            class="text-xs font-bold px-2.5 py-1.5 rounded-badge border cursor-pointer focus:outline-none <?= match($ord['status']) {
                                                'completed'  => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                'processing' => 'bg-blue-50 text-blue-700 border-blue-200',
                                                'cancelled'  => 'bg-rose-50 text-rose-700 border-rose-200',
                                                default      => 'bg-amber-50 text-amber-700 border-amber-200',
                                            } ?>">
                                            <option value="pending" <?= $ord['status'] === 'pending' ? 'selected' : '' ?>>🟡 PENDING</option>
                                            <option value="processing" <?= $ord['status'] === 'processing' ? 'selected' : '' ?>>🔵 PROCESSING</option>
                                            <option value="completed" <?= $ord['status'] === 'completed' ? 'selected' : '' ?>>🟢 COMPLETED</option>
                                            <option value="cancelled" <?= $ord['status'] === 'cancelled' ? 'selected' : '' ?>>🔴 CANCELLED</option>
                                        </select>
                                    </form>
                                </td>

                                <!-- Detail Action Button -->
                                <td class="px-6 py-4 text-right">
                                    <button 
                                        type="button" 
                                        @click='openDetail(<?= json_encode($ord) ?>)'
                                        class="px-3 py-1.5 rounded-btn bg-slate-100 hover:bg-brand-50 hover:text-brand-600 text-slate-700 font-bold transition border border-slate-200/80 apple-tap text-xs">
                                        View Details
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- Order Detail Modal (Translucent Apple Blur, Zero Shadow) -->
    <div 
        x-cloak 
        x-show="detailModalOpen" 
        class="fixed inset-0 z-50 overflow-y-auto" 
        aria-labelledby="modal-title" 
        role="dialog" 
        aria-modal="true">
        
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            
            <div 
                x-show="detailModalOpen"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click="detailModalOpen = false" 
                class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity">
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div 
                x-show="detailModalOpen"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white rounded-modal text-left overflow-hidden transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-slate-200/90">
                
                <template x-if="selectedOrder">
                    <div>
                        <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                            <div>
                                <h3 class="text-base font-extrabold text-slate-900 tracking-tight">Order Details</h3>
                                <p class="text-xs font-mono text-slate-500" x-text="selectedOrder.order_number"></p>
                            </div>
                            <button type="button" @click="detailModalOpen = false" class="text-slate-400 hover:text-slate-600 p-1 rounded-btn apple-tap">
                                <i class="ph ph-x text-lg"></i>
                            </button>
                        </div>

                        <div class="p-6 space-y-6 text-xs max-h-[70vh] overflow-y-auto">
                            
                            <!-- Customer Box -->
                            <div class="p-4 rounded-card bg-slate-50 border border-slate-200/80 space-y-2">
                                <span class="font-extrabold uppercase text-slate-400 text-[10px] tracking-wider block">Customer &amp; Delivery Information</span>
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <span class="text-slate-400 block text-[11px]">Recipient Name:</span>
                                        <span class="font-bold text-slate-900" x-text="selectedOrder.customer_name"></span>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 block text-[11px]">WhatsApp / Phone:</span>
                                        <span class="font-bold text-brand-600" x-text="selectedOrder.customer_phone"></span>
                                    </div>
                                    <div class="col-span-2">
                                        <span class="text-slate-400 block text-[11px]">Delivery Address:</span>
                                        <p class="font-medium text-slate-700 leading-relaxed" x-text="selectedOrder.customer_address"></p>
                                    </div>
                                    <template x-if="selectedOrder.customer_notes">
                                        <div class="col-span-2 pt-1 border-t border-slate-200/60">
                                            <span class="text-slate-400 block text-[11px]">Customer Notes:</span>
                                            <p class="font-medium text-amber-700 italic" x-text="selectedOrder.customer_notes"></p>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Ordered Items List -->
                            <div class="space-y-3">
                                <span class="font-extrabold uppercase text-slate-400 text-[10px] tracking-wider block">Ordered Items</span>
                                <div class="border border-slate-200/80 rounded-card overflow-hidden divide-y divide-slate-100">
                                    <template x-for="item in selectedOrder.items" :key="item.id">
                                        <div class="p-3.5 flex items-center justify-between gap-3">
                                            <div class="min-w-0">
                                                <h4 class="font-bold text-slate-900 truncate" x-text="item.product_name"></h4>
                                                <span class="text-[11px] text-slate-400" x-text="item.quantity + 'x @ ' + formatCurrency(item.price)"></span>
                                            </div>
                                            <span class="font-extrabold text-slate-900" x-text="formatCurrency(item.subtotal)"></span>
                                        </div>
                                    </template>
                                </div>

                                <div class="flex justify-between items-baseline p-3 bg-brand-50 rounded-card border border-brand-200/80">
                                    <span class="font-bold text-slate-700">Total Order Amount:</span>
                                    <span class="text-base font-black text-brand-600 tracking-tight" x-text="formatCurrency(selectedOrder.total_amount)"></span>
                                </div>
                            </div>

                        </div>

                        <div class="p-6 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between">
                            <button type="button" @click="detailModalOpen = false" class="px-5 py-2.5 rounded-btn bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200/80 text-xs font-bold apple-tap">
                                Close
                            </button>

                            <template x-if="selectedOrder.whatsapp_url">
                                <a 
                                    :href="selectedOrder.whatsapp_url" 
                                    target="_blank" 
                                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-btn bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold border border-brand-500/20 transition apple-tap">
                                    <i class="ph ph-whatsapp-logo text-base"></i>
                                    <span>Open Chat in WhatsApp Web</span>
                                </a>
                            </template>
                        </div>
                    </div>
                </template>

            </div>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
