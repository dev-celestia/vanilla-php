<?php
/**
 * Add / Edit Product Form
 */
$active_menu = 'products';
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../helpers/format.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/csrf.php';
require_once __DIR__ . '/../helpers/upload.php';

$db = getDB();
$editId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$isEdit = $editId > 0;
$page_title = $isEdit ? 'Ubah Informasi Produk' : 'Tambah Produk Baru';

$product = [
    'id'          => null,
    'category_id' => '',
    'name'        => '',
    'slug'        => '',
    'description' => '',
    'price'       => '',
    'promo_price' => '',
    'stock'       => '10',
    'image'       => '',
    'is_featured' => 0,
    'is_active'   => 1
];

// Fetch categories
$categories = [];
if ($db) {
    $catStmt = $db->query("SELECT id, name FROM categories ORDER BY name ASC");
    $categories = $catStmt->fetchAll();
}

// Load existing data if editing
if ($isEdit && $db) {
    $stmt = $db->prepare("SELECT * FROM products WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $editId]);
    $found = $stmt->fetch();
    if ($found) {
        $product = $found;
    } else {
        set_flash('error', 'Produk tidak ditemukan.');
        header('Location: ' . base_url('admin/products.php'));
        exit;
    }
}

$error = null;

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Sesi form telah kedaluwarsa. Silakan muat ulang dan coba lagi.';
    } else {
        $name = trim($_POST['name'] ?? '');
        $categoryId = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
        $price = (float)($_POST['price'] ?? 0);
        $promoPrice = !empty($_POST['promo_price']) ? (float)$_POST['promo_price'] : null;
        $stock = max(0, (int)($_POST['stock'] ?? 0));
        $description = trim($_POST['description'] ?? '');
        $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        $imageInputUrl = trim($_POST['image_url'] ?? '');

        if (empty($name)) {
            $error = 'Nama produk wajib diisi.';
        } elseif ($price <= 0) {
            $error = 'Harga produk harus lebih dari 0.';
        } else {
            $slug = slugify($name);

            // Handle file upload if present
            $imageFilename = $product['image']; // Default keep old image

            if (!empty($_FILES['image_file']['name'])) {
                $uploadResult = handle_image_upload($_FILES['image_file']);
                if ($uploadResult['success']) {
                    // Delete old file if local
                    if (!empty($product['image']) && !str_starts_with($product['image'], 'http')) {
                        delete_uploaded_image($product['image']);
                    }
                    $imageFilename = $uploadResult['filename'];
                } else {
                    $error = $uploadResult['message'];
                }
            } elseif (!empty($imageInputUrl)) {
                $imageFilename = $imageInputUrl;
            }

            if (!$error && $db) {
                try {
                    // Ensure unique slug
                    $slugCheckSql = "SELECT id FROM products WHERE slug = :slug" . ($isEdit ? " AND id != :id" : "");
                    $slugStmt = $db->prepare($slugCheckSql);
                    $slugParams = [':slug' => $slug];
                    if ($isEdit) $slugParams[':id'] = $editId;
                    $slugStmt->execute($slugParams);

                    if ($slugStmt->fetch()) {
                        $slug .= '-' . time();
                    }

                    if ($isEdit) {
                        $updateSql = "UPDATE products SET 
                                      category_id = :category_id, 
                                      name = :name, 
                                      slug = :slug, 
                                      description = :description, 
                                      price = :price, 
                                      promo_price = :promo_price, 
                                      stock = :stock, 
                                      image = :image, 
                                      is_featured = :is_featured, 
                                      is_active = :is_active 
                                      WHERE id = :id";
                        $stmt = $db->prepare($updateSql);
                        $stmt->execute([
                            ':category_id' => $categoryId,
                            ':name'        => $name,
                            ':slug'        => $slug,
                            ':description' => $description,
                            ':price'       => $price,
                            ':promo_price' => $promoPrice,
                            ':stock'       => $stock,
                            ':image'       => $imageFilename,
                            ':is_featured' => $isFeatured,
                            ':is_active'   => $isActive,
                            ':id'          => $editId
                        ]);

                        set_flash('success', 'Produk berhasil diperbarui.');
                    } else {
                        $insertSql = "INSERT INTO products (category_id, name, slug, description, price, promo_price, stock, image, is_featured, is_active) 
                                      VALUES (:category_id, :name, :slug, :description, :price, :promo_price, :stock, :image, :is_featured, :is_active)";
                        $stmt = $db->prepare($insertSql);
                        $stmt->execute([
                            ':category_id' => $categoryId,
                            ':name'        => $name,
                            ':slug'        => $slug,
                            ':description' => $description,
                            ':price'       => $price,
                            ':promo_price' => $promoPrice,
                            ':stock'       => $stock,
                            ':image'       => $imageFilename,
                            ':is_featured' => $isFeatured,
                            ':is_active'   => $isActive
                        ]);

                        set_flash('success', 'Produk baru berhasil ditambahkan.');
                    }

                    header('Location: ' . base_url('admin/products.php'));
                    exit;

                } catch (PDOException $e) {
                    $error = 'Gagal menyimpan produk: ' . $e->getMessage();
                }
            }
        }
    }
}

require_once __DIR__ . '/includes/admin_header.php';
?>

<div class="max-w-4xl mx-auto space-y-6">
    
    <!-- Top back link -->
    <div class="flex items-center justify-between">
        <a href="<?= base_url('admin/products.php') ?>" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-slate-900 transition apple-tap">
            <i class="ph ph-arrow-left text-sm"></i>
            <span>Kembali ke Daftar Produk</span>
        </a>
        <?php if ($isEdit): ?>
            <a href="<?= base_url('product.php?id=' . $editId) ?>" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-bold text-brand-600 hover:underline">
                <i class="ph ph-arrow-square-out text-xs"></i>
                <span>Lihat di Website Toko</span>
            </a>
        <?php endif; ?>
    </div>

    <?php if (!empty($error)): ?>
        <?= ui_alert(sanitize($error), 'danger', ['dismissible' => true]) ?>
    <?php endif; ?>

    <!-- Main Form Container (Zero Shadow, Crisp Border, Token Radius) -->
    <form action="" method="POST" enctype="multipart/form-data" class="bg-white rounded-card border border-slate-200/80 p-6 sm:p-8 space-y-8" x-data="{
        previewImage: '<?= upload_url($product['image'] ?? '') ?>',
        handleFileSelect(e) {
            const file = e.target.files[0];
            if (file) {
                this.previewImage = URL.createObjectURL(file);
            }
        }
    }">
        <?= csrf_field() ?>

        <!-- Basic Information Section -->
        <div class="space-y-4">
            <h3 class="text-sm font-extrabold text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2 tracking-tight">
                <i class="ph ph-package text-base text-brand-600"></i>
                <span>Informasi Dasar Produk</span>
            </h3>

            <div>
                <?= ui_input('name', [
                    'label'       => 'Nama Produk',
                    'placeholder' => 'Contoh: Headphone Wireless ANC Pro',
                    'value'       => $_POST['name'] ?? $product['name'],
                    'required'    => true,
                ]) ?>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <?php
                    $catOptions = ['' => 'Pilih Kategori Produk...'];
                    foreach ($categories as $cat) {
                        $catOptions[$cat['id']] = $cat['name'];
                    }
                    $selectedCat = (string)(isset($_POST['category_id']) ? $_POST['category_id'] : $product['category_id']);
                ?>
                <?= ui_select('category_id', $catOptions, [
                    'label'    => 'Kategori Produk',
                    'selected' => $selectedCat,
                ]) ?>

                <?= ui_input('stock', [
                    'label'       => 'Jumlah Stok Tersedia',
                    'type'        => 'number',
                    'placeholder' => 'Contoh: 25',
                    'value'       => $_POST['stock'] ?? $product['stock'],
                    'required'    => true,
                    'attrs'       => 'min="0"',
                ]) ?>
            </div>
        </div>

        <!-- Pricing Section -->
        <div class="space-y-4 pt-4 border-t border-slate-100">
            <h3 class="text-sm font-extrabold text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2 tracking-tight">
                <i class="ph ph-currency-dollar text-base text-brand-600"></i>
                <span>Harga & Promo</span>
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <?= ui_input('price', [
                    'label'       => 'Harga Normal (Rp)',
                    'type'        => 'number',
                    'placeholder' => 'Contoh: 750000',
                    'value'       => $_POST['price'] ?? $product['price'],
                    'required'    => true,
                    'helper'      => 'Masukkan angka tanpa titik atau koma.',
                    'attrs'       => 'min="1" step="100"',
                ]) ?>

                <?= ui_input('promo_price', [
                    'label'       => 'Harga Diskon / Promo (Rp) (Opsional)',
                    'type'        => 'number',
                    'placeholder' => 'Contoh: 599000',
                    'value'       => $_POST['promo_price'] ?? $product['promo_price'],
                    'helper'      => 'Kosongkan jika tidak ada harga diskon.',
                    'attrs'       => 'min="0" step="100"',
                ]) ?>
            </div>
        </div>

        <!-- Photo Section -->
        <div class="space-y-4 pt-4 border-t border-slate-100">
            <h3 class="text-sm font-extrabold text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2 tracking-tight">
                <i class="ph ph-image text-base text-brand-600"></i>
                <span>Foto Produk</span>
            </h3>

            <div class="flex flex-col sm:flex-row items-start gap-6">
                <!-- Preview Thumbnail (Zero Shadow, Crisp Border) -->
                <div class="w-32 h-32 rounded-card overflow-hidden border border-slate-200/90 bg-slate-50 flex items-center justify-center flex-shrink-0">
                    <img :src="previewImage" alt="Preview Foto" class="w-full h-full object-cover">
                </div>

                <div class="space-y-3 flex-1 min-w-0">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 tracking-tight">Unggah Foto dari Komputer</label>
                        <input 
                            type="file" 
                            name="image_file" 
                            accept="image/png, image/jpeg, image/webp" 
                            @change="handleFileSelect($event)"
                            class="block w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-btn file:border-0 file:text-xs file:font-bold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 transition cursor-pointer"
                        >
                        <p class="text-[11px] text-slate-400 mt-1">Format: JPG, PNG, WEBP. Maksimal 3 MB.</p>
                    </div>

                    <div>
                        <?= ui_input('image_url', [
                            'label'       => 'Atau Gunakan Link Gambar (URL Eksternal)',
                            'type'        => 'url',
                            'placeholder' => 'https://images.unsplash.com/...',
                            'value'       => str_starts_with($product['image'] ?? '', 'http') ? $product['image'] : '',
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Description Section -->
        <div class="space-y-4 pt-4 border-t border-slate-100">
            <h3 class="text-sm font-extrabold text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2 tracking-tight">
                <i class="ph ph-text-align-left text-base text-brand-600"></i>
                <span>Deskripsi & Spesifikasi Produk</span>
            </h3>

            <?= ui_textarea('description', [
                'placeholder' => 'Tuliskan deskripsi lengkap, keunggulan, spesifikasi teknis, garansi, atau isi dalam paket...',
                'rows'        => 6,
                'value'       => $_POST['description'] ?? $product['description'],
            ]) ?>
        </div>

        <!-- Status & Visibility Toggles -->
        <div class="space-y-4 pt-4 border-t border-slate-100">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <?php 
                    $isActiveChecked = (!isset($_POST['is_active']) && $product['is_active']) || (isset($_POST['is_active']) && $_POST['is_active'] == 1);
                    $isFeaturedChecked = (!isset($_POST['is_featured']) && $product['is_featured']) || (isset($_POST['is_featured']) && $_POST['is_featured'] == 1);
                ?>
                <div class="p-4 rounded-card bg-slate-50 border border-slate-200/80">
                    <?= ui_toggle('is_active', 'Tayang di Website (Aktif)', $isActiveChecked, [
                        'helper' => 'Produk dapat dilihat dan dipesan oleh pengunjung toko.',
                    ]) ?>
                </div>

                <div class="p-4 rounded-card bg-slate-50 border border-slate-200/80">
                    <?= ui_toggle('is_featured', '⭐ Jadikan Produk Unggulan', $isFeaturedChecked, [
                        'helper' => 'Tampilkan tanda rekomendasi khusus di katalog.',
                    ]) ?>
                </div>
            </div>
        </div>

        <!-- Form Action Buttons -->
        <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
            <?= ui_button('Batal', [
                'variant' => 'secondary',
                'size'    => 'md',
                'href'    => base_url('admin/products.php'),
            ]) ?>

            <?= ui_button($isEdit ? 'Simpan Perubahan' : 'Simpan Produk Baru', [
                'variant' => 'primary',
                'type'    => 'submit',
                'size'    => 'md',
                'icon'    => 'check',
            ]) ?>
        </div>

    </form>

</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
