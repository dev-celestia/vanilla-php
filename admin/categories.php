<?php
/**
 * Admin Categories Management (CRUD)
 */
$active_menu = 'categories';
$page_title = 'Kelola Kategori Produk';
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../helpers/format.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/csrf.php';

$db = getDB();
$error = null;

// Handle Add / Edit Category
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Sesi form kadaluarsa. Silakan coba lagi.';
    } else {
        $action = $_POST['action'] ?? '';
        
        if ($action === 'create' || $action === 'update') {
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $isActive = isset($_POST['is_active']) ? 1 : 0;
            $catId = (int)($_POST['category_id'] ?? 0);

            if (empty($name)) {
                $error = 'Nama kategori wajib diisi.';
            } elseif ($db) {
                $slug = slugify($name);
                try {
                    if ($action === 'create') {
                        // Check slug
                        $check = $db->prepare("SELECT id FROM categories WHERE slug = :slug");
                        $check->execute([':slug' => $slug]);
                        if ($check->fetch()) $slug .= '-' . time();

                        $stmt = $db->prepare("INSERT INTO categories (name, slug, description, is_active) VALUES (:name, :slug, :description, :is_active)");
                        $stmt->execute([
                            ':name'        => $name,
                            ':slug'        => $slug,
                            ':description' => $description,
                            ':is_active'   => $isActive
                        ]);
                        set_flash('success', 'Kategori baru berhasil ditambahkan.');
                    } else {
                        $stmt = $db->prepare("UPDATE categories SET name = :name, slug = :slug, description = :description, is_active = :is_active WHERE id = :id");
                        $stmt->execute([
                            ':name'        => $name,
                            ':slug'        => $slug,
                            ':description' => $description,
                            ':is_active'   => $isActive,
                            ':id'          => $catId
                        ]);
                        set_flash('success', 'Kategori berhasil diperbarui.');
                    }

                    header('Location: ' . base_url('admin/categories.php'));
                    exit;
                } catch (PDOException $e) {
                    $error = 'Gagal menyimpan kategori: ' . $e->getMessage();
                }
            }
        } elseif ($action === 'delete') {
            $catId = (int)($_POST['category_id'] ?? 0);
            if ($catId > 0 && $db) {
                try {
                    $stmt = $db->prepare("DELETE FROM categories WHERE id = :id");
                    $stmt->execute([':id' => $catId]);
                    set_flash('success', 'Kategori berhasil dihapus.');
                    header('Location: ' . base_url('admin/categories.php'));
                    exit;
                } catch (PDOException $e) {
                    $error = 'Gagal menghapus kategori: ' . $e->getMessage();
                }
            }
        }
    }
}

// Fetch all categories with product count
$categories = [];
if ($db) {
    $sql = "SELECT c.*, COUNT(p.id) as product_count 
            FROM categories c 
            LEFT JOIN products p ON c.id = p.category_id 
            GROUP BY c.id 
            ORDER BY c.name ASC";
    $stmt = $db->query($sql);
    $categories = $stmt->fetchAll();
}

require_once __DIR__ . '/includes/admin_header.php';
?>

<div class="space-y-6" x-data="{
    modalOpen: false,
    modalTitle: 'Tambah Kategori Baru',
    modalAction: 'create',
    catId: 0,
    catName: '',
    catDesc: '',
    catActive: true,
    
    openAdd() {
        this.modalTitle = 'Tambah Kategori Baru';
        this.modalAction = 'create';
        this.catId = 0;
        this.catName = '';
        this.catDesc = '';
        this.catActive = true;
        this.modalOpen = true;
    },

    openEdit(cat) {
        this.modalTitle = 'Edit Kategori: ' + cat.name;
        this.modalAction = 'update';
        this.catId = cat.id;
        this.catName = cat.name;
        this.catDesc = cat.description || '';
        this.catActive = cat.is_active == 1;
        this.modalOpen = true;
    }
}">

    <!-- Top Action Bar (Zero Shadow, Crisp Border) -->
    <div class="bg-white p-6 rounded-card border border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-base font-extrabold text-slate-900 tracking-tight">Daftar Kategori Produk (<?= count($categories) ?>)</h2>
            <p class="text-xs text-slate-400">Kelompokkan produk dalam kategori agar pembeli lebih mudah menjelajah</p>
        </div>
        <button 
            type="button" 
            @click="openAdd()"
            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-btn bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold border border-brand-500/20 transition apple-tap">
            <i class="ph ph-plus-circle text-base"></i>
            <span>Tambah Kategori Baru</span>
        </button>
    </div>

    <!-- Category Grid / Table (Zero Shadow, Crisp Border) -->
    <div class="bg-white rounded-card border border-slate-200/80 overflow-hidden">
        <?php if (empty($categories)): ?>
            <?= ui_empty_state('Belum Ada Kategori', 'Belum ada kategori produk. Silakan tambahkan kategori pertama Anda.', [
                'icon'       => 'tag',
                'actionHtml' => '<button type="button" @click="openAdd()" class="px-5 py-2 rounded-btn bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs apple-tap">Tambah Kategori</button>'
            ]) ?>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Nama Kategori</th>
                            <th class="px-6 py-4">Slug URL</th>
                            <th class="px-6 py-4">Jumlah Produk</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php foreach ($categories as $cat): ?>
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="px-6 py-4">
                                    <span class="font-bold text-slate-900 block tracking-tight"><?= sanitize($cat['name']) ?></span>
                                    <?php if (!empty($cat['description'])): ?>
                                        <span class="text-[11px] text-slate-400 block max-w-sm truncate"><?= sanitize($cat['description']) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 font-mono text-slate-500 text-[11px]">
                                    <?= sanitize($cat['slug']) ?>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="<?= base_url('admin/products.php?category=' . $cat['id']) ?>" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-badge bg-brand-50 text-brand-700 font-bold text-[11px] border border-brand-200/80 hover:bg-brand-100 transition apple-tap">
                                        <span><?= $cat['product_count'] ?> Produk</span>
                                    </a>
                                </td>
                                <td class="px-6 py-4">
                                    <?= ui_badge($cat['is_active'] ? 'Aktif' : 'Nonaktif', $cat['is_active'] ? 'brand' : 'neutral', ['dot' => true]) ?>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button 
                                            type="button" 
                                            @click='openEdit(<?= json_encode($cat) ?>)'
                                            class="p-2 rounded-btn bg-slate-100 hover:bg-brand-50 hover:text-brand-600 text-slate-700 font-bold transition border border-slate-200/80 apple-tap" 
                                            title="Edit Kategori">
                                            <i class="ph ph-pencil-simple text-sm"></i>
                                        </button>

                                        <form action="<?= base_url('admin/categories.php') ?>" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="category_id" value="<?= $cat['id'] ?>">
                                            <button type="submit" class="p-2 rounded-btn bg-slate-100 hover:bg-rose-50 hover:text-rose-600 text-slate-700 font-bold transition border border-slate-200/80 apple-tap" title="Hapus Kategori">
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

    <!-- Modal Form (Add / Edit) (Translucent Apple Blur, Zero Shadow) -->
    <div 
        x-cloak 
        x-show="modalOpen" 
        class="fixed inset-0 z-50 overflow-y-auto" 
        aria-labelledby="modal-title" 
        role="dialog" 
        aria-modal="true">
        
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            
            <div 
                x-show="modalOpen"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click="modalOpen = false" 
                class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity">
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div 
                x-show="modalOpen"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white rounded-modal text-left overflow-hidden transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200/90">
                
                <form action="<?= base_url('admin/categories.php') ?>" method="POST">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" :value="modalAction">
                    <input type="hidden" name="category_id" :value="catId">

                    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="text-base font-extrabold text-slate-900 tracking-tight" x-text="modalTitle"></h3>
                        <button type="button" @click="modalOpen = false" class="text-slate-400 hover:text-slate-600 p-1 rounded-btn apple-tap">
                            <i class="ph ph-x text-lg"></i>
                        </button>
                    </div>

                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5 tracking-tight">Nama Kategori <span class="text-rose-500">*</span></label>
                            <input 
                                type="text" 
                                name="name" 
                                required 
                                x-model="catName"
                                placeholder="Contoh: Aksesoris Gadget" 
                                class="w-full px-4 py-2.5 text-xs rounded-input border border-slate-200/90 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20"
                            >
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5 tracking-tight">Deskripsi Singkat</label>
                            <textarea 
                                name="description" 
                                rows="3" 
                                x-model="catDesc"
                                placeholder="Keterangan singkat produk dalam kategori ini..." 
                                class="w-full px-4 py-2.5 text-xs rounded-input border border-slate-200/90 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20"></textarea>
                        </div>

                        <div class="p-3 rounded-card bg-slate-50 border border-slate-200/80">
                            <?= ui_toggle('is_active', 'Aktifkan Kategori', true, [
                                'attrs' => 'x-model="catActive"',
                            ]) ?>
                        </div>
                    </div>

                    <div class="p-6 border-t border-slate-100 bg-slate-50/50 flex items-center justify-end gap-3">
                        <button type="button" @click="modalOpen = false" class="px-5 py-2.5 rounded-btn bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200/80 text-xs font-bold apple-tap">
                            Batal
                        </button>
                        <button type="submit" class="px-6 py-2.5 rounded-btn bg-brand-600 hover:bg-brand-700 text-white border border-brand-500/20 text-xs font-bold apple-tap">
                            Simpan Kategori
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
