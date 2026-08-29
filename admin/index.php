<?php
/**
 * Admin Dashboard Main Index
 */
$active_menu = 'dashboard';
$page_title = 'Dashboard Overview';
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../helpers/format.php';
require_once __DIR__ . '/../helpers/auth.php';

$db = getDB();

$totalProducts = 0;
$activeProducts = 0;
$totalCategories = 0;
$totalOrders = 0;
$pendingOrders = 0;
$totalRevenue = 0;
$lowStockProducts = [];
$recentOrders = [];
$recentProducts = [];

if ($db) {
    try {
        // Product metrics
        $totalProducts = (int)$db->query("SELECT COUNT(*) FROM products")->fetchColumn();
        $activeProducts = (int)$db->query("SELECT COUNT(*) FROM products WHERE is_active = 1")->fetchColumn();
        $totalCategories = (int)$db->query("SELECT COUNT(*) FROM categories")->fetchColumn();

        // Orders metrics
        $totalOrders = (int)$db->query("SELECT COUNT(*) FROM orders")->fetchColumn();
        $pendingOrders = (int)$db->query("SELECT COUNT(*) FROM orders WHERE status = 'pending'")->fetchColumn();
        $totalRevenue = (float)$db->query("SELECT COALESCE(SUM(total_amount), 0) FROM orders WHERE status IN ('completed', 'processing')")->fetchColumn();

        // Low stock products (stock <= 5)
        $lowStockStmt = $db->query("SELECT id, name, stock, image FROM products WHERE stock <= 5 AND is_active = 1 ORDER BY stock ASC LIMIT 5");
        $lowStockProducts = $lowStockStmt->fetchAll();

        // Recent orders
        $orderStmt = $db->query("SELECT * FROM orders ORDER BY created_at DESC LIMIT 6");
        $recentOrders = $orderStmt->fetchAll();

        // Recent products
        $prodStmt = $db->query("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC LIMIT 4");
        $recentProducts = $prodStmt->fetchAll();

    } catch (PDOException $e) {
        error_log("Dashboard query error: " . $e->getMessage());
    }
}

require_once __DIR__ . '/includes/admin_header.php';
?>

<!-- Metric Cards Grid (Using ui_stat_card Primitive, Zero Shadow) -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <?= ui_stat_card('Total Products', $totalProducts, [
        'icon'     => 'package',
        'subtitle' => $activeProducts . ' Active in Store',
    ]) ?>

    <?= ui_stat_card('Categories', $totalCategories, [
        'icon'     => 'tags',
        'subtitle' => 'Catalog Structure',
    ]) ?>

    <?= ui_stat_card('WhatsApp Orders', $totalOrders, [
        'icon'      => 'shopping-cart',
        'subtitle'  => $pendingOrders . ' Awaiting Processing',
        'trend'     => $pendingOrders > 0 ? $pendingOrders . ' New' : null,
        'trendType' => 'up',
    ]) ?>

    <?= ui_stat_card('Total Revenue', format_rupiah($totalRevenue), [
        'icon'     => 'banknote',
        'subtitle' => 'Completed / In Process',
    ]) ?>
</div>

<!-- Low Stock Warning Alert if any -->
<?php if (!empty($lowStockProducts)): ?>
    <div class="mb-8 p-5 bg-amber-50 border border-amber-200/90 rounded-card">
        <div class="flex items-center gap-2 text-amber-800 font-semibold text-xs mb-3">
            <i class="ph ph-warning text-base text-amber-600"></i>
            <span>Warning: <?= count($lowStockProducts) ?> products are running low on stock (≤ 5 units)!</span>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            <?php foreach ($lowStockProducts as $low): ?>
                <div class="bg-white p-3 rounded-btn border border-amber-200 flex items-center justify-between gap-3 text-xs">
                    <span class="font-semibold text-slate-800 truncate"><?= sanitize($low['name']) ?></span>
                    <?= ui_badge('Stock: ' . $low['stock'], 'danger') ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<!-- 2-Column Content: Recent Orders & Quick Actions -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
    
    <!-- Recent Orders Table (Zero Shadow, Crisp Border) -->
    <div class="lg:col-span-8 bg-white rounded-card border border-slate-200/80 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-sm font-semibold text-slate-900 tracking-tight">Recent WhatsApp Orders</h2>
                <p class="text-xs text-slate-400 mt-0.5">Incoming transactions from WhatsApp checkout form</p>
            </div>
            <a href="<?= base_url('admin/orders.php') ?>" class="text-xs font-semibold text-brand-600 hover:text-brand-700">View All →</a>
        </div>

        <?php if (empty($recentOrders)): ?>
            <?= ui_empty_state('No Orders Yet', 'There are no customer orders recorded yet.', ['icon' => 'tray', 'class' => 'my-0 border-0']) ?>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 border-b border-slate-100 text-slate-400 font-semibold uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3.5">Order Number</th>
                            <th class="px-6 py-3.5">Customer</th>
                            <th class="px-6 py-3.5">Total</th>
                            <th class="px-6 py-3.5">Status</th>
                            <th class="px-6 py-3.5 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php foreach ($recentOrders as $ord): ?>
                            <?php 
                                $statusVariant = match($ord['status']) {
                                    'completed'  => 'success',
                                    'processing' => 'info',
                                    'cancelled'  => 'danger',
                                    default      => 'warning',
                                };
                            ?>
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-6 py-4 font-mono font-semibold text-slate-900">
                                    <?= sanitize($ord['order_number']) ?>
                                    <span class="block font-sans text-[10px] text-slate-400 font-normal"><?= date('d M Y, H:i', strtotime($ord['created_at'])) ?></span>
                                </td>
                                <td class="px-6 py-4 font-semibold text-slate-800">
                                    <?= sanitize($ord['customer_name']) ?>
                                    <span class="block text-[11px] text-slate-400"><?= sanitize($ord['customer_phone']) ?></span>
                                </td>
                                <td class="px-6 py-4 font-semibold text-brand-600">
                                    <?= format_rupiah($ord['total_amount']) ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?= ui_badge(strtoupper($ord['status']), $statusVariant, ['dot' => true]) ?>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <?= ui_button('Details', [
                                        'variant' => 'secondary',
                                        'size'    => 'xs',
                                        'href'    => base_url('admin/orders.php?search=' . urlencode($ord['order_number'])),
                                    ]) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- Quick Shortcuts & Product List -->
    <div class="lg:col-span-4 space-y-6">
        
        <!-- Quick Action Card -->
        <div class="bg-white p-6 rounded-card border border-slate-200/80 space-y-3">
            <h3 class="text-sm font-semibold text-slate-900 mb-2 tracking-tight">Quick Actions</h3>
            
            <a href="<?= base_url('admin/product-form.php') ?>" class="w-full flex items-center justify-between p-3.5 rounded-btn bg-brand-50 text-brand-800 border border-brand-200/80 hover:bg-brand-100 font-semibold text-xs transition apple-tap">
                <div class="flex items-center gap-2.5">
                    <i class="ph ph-plus-circle text-base text-brand-600"></i>
                    <span>Add New Product</span>
                </div>
                <i class="ph ph-caret-right text-base text-brand-600"></i>
            </a>

            <a href="<?= base_url('admin/categories.php') ?>" class="w-full flex items-center justify-between p-3.5 rounded-btn bg-slate-50 text-slate-700 border border-slate-200/80 hover:bg-slate-100 font-semibold text-xs transition apple-tap">
                <div class="flex items-center gap-2.5">
                    <i class="ph ph-tag text-base text-slate-500"></i>
                    <span>Manage Categories</span>
                </div>
                <i class="ph ph-caret-right text-base text-slate-400"></i>
            </a>

            <a href="<?= base_url('admin/settings.php') ?>" class="w-full flex items-center justify-between p-3.5 rounded-btn bg-slate-50 text-slate-700 border border-slate-200/80 hover:bg-slate-100 font-semibold text-xs transition apple-tap">
                <div class="flex items-center gap-2.5">
                    <i class="ph ph-palette text-base text-slate-500"></i>
                    <span>Theme Colors &amp; Styling</span>
                </div>
                <i class="ph ph-caret-right text-base text-slate-400"></i>
            </a>
        </div>

        <!-- Latest Products Box -->
        <div class="bg-white p-6 rounded-card border border-slate-200/80">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-slate-900 tracking-tight">Recent Products</h3>
                <a href="<?= base_url('admin/products.php') ?>" class="text-[11px] font-semibold text-brand-600 hover:underline">All</a>
            </div>

            <div class="space-y-3">
                <?php foreach ($recentProducts as $rp): ?>
                    <div class="flex items-center justify-between gap-3 text-xs">
                        <div class="flex items-center gap-3 min-w-0">
                            <img src="<?= upload_url($rp['image']) ?>" alt="<?= sanitize($rp['name']) ?>" class="w-10 h-10 rounded-btn object-cover border border-slate-200 flex-shrink-0 bg-slate-50">
                            <div class="min-w-0">
                                <h4 class="font-semibold text-slate-800 truncate tracking-tight"><?= sanitize($rp['name']) ?></h4>
                                <span class="text-[11px] text-brand-600 font-semibold"><?= format_rupiah($rp['price']) ?></span>
                            </div>
                        </div>
                        <a href="<?= base_url('admin/product-form.php?id=' . $rp['id']) ?>" class="text-slate-400 hover:text-slate-700 p-1 apple-tap" title="Edit Product">
                            <i class="ph ph-pencil-simple text-sm"></i>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>

</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
