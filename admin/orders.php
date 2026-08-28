<?php
/**
 * Admin WhatsApp Orders Management
 */
$active_menu = 'orders';
$page_title = 'Riwayat Pesanan WhatsApp';
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
                set_flash('success', 'Status pesanan #' . $orderId . ' berhasil diperbarui menjadi ' . strtoupper($newStatus));
                header('Location: ' . base_url('admin/orders.php'));
                exit;
            } catch (PDOException $e) {
                set_flash('error', 'Gagal memperbarui status: ' . $e->getMessage());
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
    }
}">

    <!-- Filter & Header (Zero Shadow, Crisp Border, Token Radius) -->
    <div class="bg-white p-6 rounded-card border border-slate-200/80 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-base font-extrabold text-slate-900 tracking-tight">Pesanan Masuk via WhatsApp (<?= count($orders) ?>)</h2>
                <p class="text-xs text-slate-400">Pantau status transaksi pemesanan langsung dari website ke chat admin</p>
            </div>
        </div>

        <form method="GET" action="<?= base_url('admin/orders.php') ?>" class="grid grid-cols-1 sm:grid-cols-12 gap-3 pt-3 border-t border-slate-100">
            <div class="sm:col-span-6 relative">
                <input 
                    type="text" 
                    name="search" 
                    value="<?= sanitize($search) ?>" 
                    placeholder="Cari kode pesanan, nama pembeli, atau no. WA..." 
                    class="w-full pl-9 pr-4 py-2.5 text-xs rounded-input bg-slate-50 border border-slate-200/90 focus:bg-white focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20"
                >
                <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-3"></i>
            </div>

            <div class="sm:col-span-4">
                <select name="status" class="w-full px-3 py-2.5 text-xs rounded-input bg-slate-50 border border-slate-200/90 focus:bg-white focus:border-brand-500 focus:outline-none cursor-pointer">
                    <option value="all" <?= $statusFilter === 'all' ? 'selected' : '' ?>>Semua Status Transaksi</option>
                    <option value="pending" <?= $statusFilter === 'pending' ? 'selected' : '' ?>>Pending (Menunggu)</option>
                    <option value="processing" <?= $statusFilter === 'processing' ? 'selected' : '' ?>>Processing (Sedang Diproses)</option>
                    <option value="completed" <?= $statusFilter === 'completed' ? 'selected' : '' ?>>Completed (Selesai)</option>
                    <option value="cancelled" <?= $statusFilter === 'cancelled' ? 'selected' : '' ?>>Cancelled (Dibatalkan)</option>
                </select>
            </div>

            <div class="sm:col-span-2 flex gap-2">
                <button type="submit" class="w-full py-2.5 px-4 rounded-btn bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition apple-tap">
                    Filter
                </button>
                <?php if (!empty($search) || $statusFilter !== 'all'): ?>
                    <a href="<?= base_url('admin/orders.php') ?>" class="p-2.5 rounded-btn bg-slate-100 text-slate-600 hover:bg-slate-200 border border-slate-200/80 text-xs font-bold transition apple-tap">
                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Orders Table (Zero Shadow, Crisp Border) -->
    <div class="bg-white rounded-card border border-slate-200/80 overflow-hidden">
        <?php if (empty($orders)): ?>
            <div class="p-16 text-center text-slate-400 text-xs">
                <i data-lucide="inbox" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i>
                Tidak ada data pesanan yang ditemukan.
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Kode & Waktu</th>
                            <th class="px-6 py-4">Data Pembeli</th>
                            <th class="px-6 py-4">Jumlah Item</th>
                            <th class="px-6 py-4">Total Belanja</th>
                            <th class="px-6 py-4">Status & Update</th>
                            <th class="px-6 py-4 text-right">Rincian</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php foreach ($orders as $ord): ?>
                            <?php 
                                $customerPhoneClean = preg_replace('/[^0-9]/', '', $ord['customer_phone']);
                                if (str_starts_with($customerPhoneClean, '0')) {
                                    $customerPhoneClean = '62' . substr($customerPhoneClean, 1);
                                }
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
                                        <i data-lucide="message-circle" class="w-3.5 h-3.5"></i>
                                        <span><?= sanitize($ord['customer_phone']) ?></span>
                                    </a>
                                </td>

                                <!-- Items Count -->
                                <td class="px-6 py-4 text-slate-600 font-medium">
                                    <?= count($ord['items']) ?> Produk
                                </td>

                                <!-- Total -->
                                <td class="px-6 py-4 font-black text-brand-600">
                                    <?= format_rupiah($ord['total_amount']) ?>
                                </td>

                                <!-- Status Dropdown -->
                                <td class="px-6 py-4">
                                    <form action="<?= base_url('admin/orders.php') ?>" method="POST" class="inline-block">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="action" value="update_status">
                                        <input type="hidden" name="order_id" value="<?= $ord['id'] ?>">
                                        <select 
                                            name="status" 
                                            onchange="this.form.submit()" 
                                            class="text-[11px] font-bold px-2.5 py-1.5 rounded-badge border focus:outline-none cursor-pointer <?= 
                                                $ord['status'] === 'completed' ? 'bg-emerald-50 text-emerald-700 border-emerald-300' : 
                                                ($ord['status'] === 'processing' ? 'bg-blue-50 text-blue-700 border-blue-300' : 
                                                ($ord['status'] === 'cancelled' ? 'bg-rose-50 text-rose-700 border-rose-300' : 'bg-amber-50 text-amber-700 border-amber-300')) 
                                            ?>">
                                            <option value="pending" <?= $ord['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                            <option value="processing" <?= $ord['status'] === 'processing' ? 'selected' : '' ?>>Processing</option>
                                            <option value="completed" <?= $ord['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                                            <option value="cancelled" <?= $ord['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                        </select>
                                    </form>
                                </td>

                                <!-- View Modal CTA -->
                                <td class="px-6 py-4 text-right">
                                    <button 
                                        type="button" 
                                        @click='openDetail(<?= json_encode($ord) ?>)'
                                        class="px-3 py-1.5 rounded-btn bg-slate-100 hover:bg-slate-200 text-slate-800 border border-slate-200/80 font-bold text-[11px] transition apple-tap">
                                        Lihat Detail
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
        role="dialog">
        
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div 
                x-show="detailModalOpen"
                @click="detailModalOpen = false" 
                class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity">
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div 
                x-show="detailModalOpen"
                class="inline-block align-bottom bg-white rounded-modal text-left overflow-hidden transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-slate-200/90">
                
                <template x-if="selectedOrder">
                    <div>
                        <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                            <div>
                                <h3 class="text-base font-extrabold text-slate-900 tracking-tight">Rincian Lengkap Pesanan</h3>
                                <p class="text-xs font-mono text-slate-500" x-text="selectedOrder.order_number"></p>
                            </div>
                            <button type="button" @click="detailModalOpen = false" class="text-slate-400 hover:text-slate-600 p-1 rounded-btn apple-tap">
                                <i data-lucide="x" class="w-5 h-5"></i>
                            </button>
                        </div>

                        <div class="p-6 space-y-6 text-xs max-h-[70vh] overflow-y-auto">
                            
                            <!-- Customer Box -->
                            <div class="p-4 rounded-card bg-slate-50 border border-slate-200/80 space-y-2">
                                <span class="font-extrabold uppercase text-slate-400 text-[10px] tracking-wider block">Informasi Pembeli & Pengiriman</span>
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <span class="text-slate-400 block text-[11px]">Nama Pelanggan:</span>
                                        <span class="font-bold text-slate-900" x-text="selectedOrder.customer_name"></span>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 block text-[11px]">WhatsApp:</span>
                                        <span class="font-bold text-brand-600" x-text="selectedOrder.customer_phone"></span>
                                    </div>
                                    <div class="col-span-2">
                                        <span class="text-slate-400 block text-[11px]">Alamat Pengiriman:</span>
                                        <p class="font-medium text-slate-700 leading-relaxed" x-text="selectedOrder.customer_address"></p>
                                    </div>
                                    <template x-if="selectedOrder.customer_notes">
                                        <div class="col-span-2 pt-1 border-t border-slate-200/60">
                                            <span class="text-slate-400 block text-[11px]">Catatan Pembeli:</span>
                                            <p class="font-medium text-amber-700 italic" x-text="selectedOrder.customer_notes"></p>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Ordered Items List -->
                            <div class="space-y-3">
                                <span class="font-extrabold uppercase text-slate-400 text-[10px] tracking-wider block">Daftar Barang Belanja</span>
                                <div class="border border-slate-200/80 rounded-card overflow-hidden divide-y divide-slate-100">
                                    <template x-for="item in selectedOrder.items" :key="item.id">
                                        <div class="p-3.5 flex items-center justify-between gap-3">
                                            <div class="min-w-0">
                                                <h4 class="font-bold text-slate-900 truncate" x-text="item.product_name"></h4>
                                                <span class="text-[11px] text-slate-400" x-text="item.quantity + 'x @ Rp ' + Number(item.price).toLocaleString('id-ID')"></span>
                                            </div>
                                            <span class="font-extrabold text-slate-900" x-text="'Rp ' + Number(item.subtotal).toLocaleString('id-ID')"></span>
                                        </div>
                                    </template>
                                </div>

                                <div class="flex justify-between items-baseline p-3 bg-brand-50 rounded-card border border-brand-200/80">
                                    <span class="font-bold text-slate-700">Total Transaksi:</span>
                                    <span class="text-base font-black text-brand-600 tracking-tight" x-text="'Rp ' + Number(selectedOrder.total_amount).toLocaleString('id-ID')"></span>
                                </div>
                            </div>

                        </div>

                        <div class="p-6 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between">
                            <button type="button" @click="detailModalOpen = false" class="px-5 py-2.5 rounded-btn bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200/80 text-xs font-bold apple-tap">
                                Tutup
                            </button>

                            <template x-if="selectedOrder.whatsapp_url">
                                <a 
                                    :href="selectedOrder.whatsapp_url" 
                                    target="_blank" 
                                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-btn bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold border border-brand-500/20 transition apple-tap">
                                    <i data-lucide="message-circle" class="w-4 h-4"></i>
                                    <span>Buka Pesan di WhatsApp Web</span>
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
