<?php
/**
 * Admin Product Management (List & Delete)
 */
$active_menu = 'products';
$page_title = 'Kelola Katalog Produk';
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../helpers/format.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/csrf.php';
require_once __DIR__ . '/../helpers/upload.php';

$db = getDB();

// Handle Delete Product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    if (verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $deleteId = (int)($_POST['product_id'] ?? 0);
        if ($deleteId > 0 && $db) {
            try {
                // Get product image to delete from disk if local
                $imgStmt = $db->prepare("SELECT image FROM products WHERE id = :id");
                $imgStmt->execute([':id' => $deleteId]);
                $img = $imgStmt->fetchColumn();

                if ($img && !str_starts_with($img, 'http')) {
                    delete_uploaded_image($img);
                }

                $delStmt = $db->prepare("DELETE FROM products WHERE id = :id");
                $delStmt->execute([':id' => $deleteId]);

                set_flash('success', 'Produk berhasil dihapus.');
                header('Location: ' . base_url('admin/products.php'));
                exit;
            } catch (PDOException $e) {
                set_flash('error', 'Gagal menghapus produk: ' . $e->getMessage());
            }
        }
    }
}

// Fetch Categories for Filter
$categories = [];
if ($db) {
    $catStmt = $db->query("SELECT id, name FROM categories ORDER BY name ASC");
    $categories = $catStmt->fetchAll();
}

// Search & Filter
$search = trim($_GET['q'] ?? '');
$categoryId = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$status = $_GET['status'] ?? 'all';

$products = [];
if ($db) {
    $sql = "SELECT p.*, c.name as category_name 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id 
            WHERE 1=1";
    $params = [];

    if (!empty($search)) {
        $sql .= " AND (p.name LIKE :search OR p.description LIKE :search)";
        $params[':search'] = '%' . $search . '%';
    }

    if ($categoryId > 0) {
        $sql .= " AND p.category_id = :category_id";
        $params[':category_id'] = $categoryId;
    }

    if ($status === 'active') {
        $sql .= " AND p.is_active = 1";
    } elseif ($status === 'inactive') {
        $sql .= " AND p.is_active = 0";
    } elseif ($status === 'out_of_stock') {
        $sql .= " AND p.stock <= 0";
    }

    $sql .= " ORDER BY p.id DESC";
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $products = $stmt->fetchAll();
}

require_once __DIR__ . '/includes/admin_header.php';
?>

<!-- Action Bar & Filter Header (Zero Shadow, Crisp Border, Token Radius) -->
<div class="bg-white p-6 rounded-card border border-slate-200/80 mb-6 space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-base font-extrabold text-slate-900 tracking-tight">Daftar Produk (<?= count($products) ?>)</h2>
            <p class="text-xs text-slate-400">Kelola informasi harga, stok, foto, dan status tayang produk di website</p>
        </div>
        <?= ui_button('Tambah Produk Baru', [
            'variant' => 'primary',
            'size'    => 'sm',
            'href'    => base_url('admin/product-form.php'),
            'icon'    => 'plus-circle',
        ]) ?>
    </div>

    <!-- Search & Filter Form -->
    <form method="GET" action="<?= base_url('admin/products.php') ?>" class="grid grid-cols-1 sm:grid-cols-12 gap-3 pt-3 border-t border-slate-100">
        <div class="sm:col-span-5 relative">
            <input 
                type="text" 
                name="q" 
                value="<?= sanitize($search) ?>" 
                placeholder="Cari nama atau deskripsi produk..." 
                class="w-full pl-9 pr-4 py-2.5 text-xs rounded-input bg-slate-50 border border-slate-200/90 focus:bg-white focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20"
            >
            <i class="ph ph-magnifying-glass text-slate-400 absolute left-3 top-3 text-sm"></i>
        </div>

        <div class="sm:col-span-3">
            <select name="category" class="w-full px-3 py-2.5 text-xs rounded-input bg-slate-50 border border-slate-200/90 focus:bg-white focus:border-brand-500 focus:outline-none cursor-pointer">
                <option value="0">Semua Kategori</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= $categoryId == $cat['id'] ? 'selected' : '' ?>>
                        <?= sanitize($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="sm:col-span-2">
            <select name="status" class="w-full px-3 py-2.5 text-xs rounded-input bg-slate-50 border border-slate-200/90 focus:bg-white focus:border-brand-500 focus:outline-none cursor-pointer">
                <option value="all" <?= $status === 'all' ? 'selected' : '' ?>>Semua Status</option>
                <option value="active" <?= $status === 'active' ? 'selected' : '' ?>>Hanya Aktif</option>
                <option value="inactive" <?= $status === 'inactive' ? 'selected' : '' ?>>Hanya Nonaktif</option>
                <option value="out_of_stock" <?= $status === 'out_of_stock' ? 'selected' : '' ?>>Stok Habis (0)</option>
            </select>
        </div>

        <div class="sm:col-span-2 flex gap-2">
            <button type="submit" class="w-full py-2.5 px-4 rounded-btn bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition apple-tap">
                Filter
            </button>
            <?php if (!empty($search) || $categoryId > 0 || $status !== 'all'): ?>
                <a href="<?= base_url('admin/products.php') ?>" class="p-2.5 rounded-btn bg-slate-100 text-slate-600 hover:bg-slate-200 border border-slate-200/80 text-xs font-bold transition apple-tap" title="Reset Filter">
                    <i class="ph ph-arrows-counter-clockwise text-sm"></i>
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Product Table (Zero Shadow, Crisp Border) -->
<div class="bg-white rounded-card border border-slate-200/80 overflow-hidden">
    <?php if (empty($products)): ?>
        <div class="p-16 text-center text-slate-400">
            <div class="w-16 h-16 rounded-card bg-slate-100 border border-slate-200/80 text-slate-400 flex items-center justify-center mx-auto mb-3">
                <i class="ph ph-package text-3xl"></i>
            </div>
            <h3 class="text-sm font-bold text-slate-800 mb-1 tracking-tight">Tidak Ada Produk yang Cocok</h3>
            <p class="text-xs text-slate-400 max-w-sm mx-auto mb-4">Silakan atur ulang filter pencarian atau tambahkan produk baru.</p>
            <?= ui_button('Tambah Produk Sekarang', [
                'variant' => 'primary',
                'size'    => 'sm',
                'href'    => base_url('admin/product-form.php'),
                'icon'    => 'plus',
            ]) ?>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Foto & Nama Produk</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Harga Normal / Promo</th>
                        <th class="px-6 py-4">Stok</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($products as $prod): ?>
                        <?php 
                            $hasPromo = !empty($prod['promo_price']) && $prod['promo_price'] < $prod['price'];
                            $isOutOfStock = $prod['stock'] <= 0;
                            $imgUrl = upload_url($prod['image']);
                        ?>
                        <tr class="hover:bg-slate-50/70 transition">
                            <!-- Product Image & Title -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3.5 min-w-[240px]">
                                    <img src="<?= $imgUrl ?>" alt="<?= sanitize($prod['name']) ?>" class="w-12 h-12 rounded-btn object-cover border border-slate-200 bg-slate-50 flex-shrink-0">
                                    <div class="min-w-0">
                                        <a href="<?= base_url('product.php?id=' . $prod['id']) ?>" target="_blank" class="font-bold text-slate-900 hover:text-brand-600 transition block truncate max-w-xs tracking-tight">
                                            <?= sanitize($prod['name']) ?>
                                        </a>
                                        <div class="flex items-center gap-2 mt-0.5">
                                            <?php if ($prod['is_featured']): ?>
                                                <span class="text-[10px] px-1.5 py-0.5 rounded-badge bg-amber-100 text-amber-700 font-extrabold border border-amber-200">⭐ Unggulan</span>
                                            <?php endif; ?>
                                            <span class="text-[10px] text-slate-400 font-mono">ID: #<?= $prod['id'] ?></span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Category -->
                            <td class="px-6 py-4 font-medium text-slate-600">
                                <?= sanitize($prod['category_name'] ?? 'Tanpa Kategori') ?>
                            </td>

                            <!-- Price & Promo -->
                            <td class="px-6 py-4">
                                <div>
                                    <span class="font-extrabold text-slate-900 block"><?= format_rupiah($hasPromo ? $prod['promo_price'] : $prod['price']) ?></span>
                                    <?php if ($hasPromo): ?>
                                        <span class="text-[11px] text-slate-400 line-through"><?= format_rupiah($prod['price']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </td>

                            <!-- Stock -->
                            <td class="px-6 py-4">
                                <span class="inline-block px-2.5 py-1 rounded-badge text-xs font-bold border <?= $isOutOfStock ? 'bg-rose-50 text-rose-700 border-rose-200' : ($prod['stock'] <= 5 ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-slate-100 text-slate-700 border-slate-200') ?>">
                                    <?= $prod['stock'] ?> unit
                                </span>
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4">
                                <?= ui_badge($prod['is_active'] ? 'Aktif' : 'Draft / Nonaktif', $prod['is_active'] ? 'brand' : 'neutral', ['dot' => true]) ?>
                            </td>

                            <!-- Action Buttons -->
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a 
                                        href="<?= base_url('admin/product-form.php?id=' . $prod['id']) ?>" 
                                        class="p-2 rounded-btn bg-slate-100 hover:bg-brand-50 hover:text-brand-600 text-slate-700 font-bold transition border border-slate-200/80 apple-tap" 
                                        title="Edit Produk">
                                        <i class="ph ph-pencil-simple text-sm"></i>
                                    </a>

                                    <form action="<?= base_url('admin/products.php') ?>" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini? Tindakan ini tidak dapat dibatalkan.')">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="product_id" value="<?= $prod['id'] ?>">
                                        <button type="submit" class="p-2 rounded-btn bg-slate-100 hover:bg-rose-50 hover:text-rose-600 text-slate-700 font-bold transition border border-slate-200/80 apple-tap" title="Hapus Produk">
                                            <i class="ph ph-trash text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
