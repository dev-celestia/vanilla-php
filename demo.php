<?php
/**
 * Interactive E-Commerce & WhatsApp Checkout Demo Page
 */
$active_nav = 'demo';
$page_title = 'Demo Katalog & WhatsApp Checkout';
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/helpers/format.php';

$db = getDB();
$categories = [];
$products = [];
$totalProducts = 0;

$categoryId = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$search = trim($_GET['q'] ?? '');
$sort = $_GET['sort'] ?? 'newest';

if ($db) {
    try {
        // Fetch active categories
        $catStmt = $db->query("SELECT * FROM categories WHERE is_active = 1 ORDER BY name ASC");
        $categories = $catStmt->fetchAll();

        // Build query for products
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.is_active = 1";
        $params = [];

        if ($categoryId > 0) {
            $sql .= " AND p.category_id = :category_id";
            $params[':category_id'] = $categoryId;
        }

        if (!empty($search)) {
            $sql .= " AND (p.name LIKE :search OR p.description LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }

        // Sorting
        switch ($sort) {
            case 'price_low':
                $sql .= " ORDER BY COALESCE(p.promo_price, p.price) ASC";
                break;
            case 'price_high':
                $sql .= " ORDER BY COALESCE(p.promo_price, p.price) DESC";
                break;
            case 'name_asc':
                $sql .= " ORDER BY p.name ASC";
                break;
            case 'newest':
            default:
                $sql .= " ORDER BY p.is_featured DESC, p.created_at DESC";
                break;
        }

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $products = $stmt->fetchAll();
        $totalProducts = count($products);

    } catch (PDOException $e) {
        error_log('Query error: ' . $e->getMessage());
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<!-- Demo Hero Banner Section (Apple Translucent Materials, Crisp Flat Edges, Zero Shadows) -->
<section class="relative bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 text-white overflow-hidden py-16 lg:py-20 border-b border-slate-800">
    <!-- Subtle backdrop accent blur -->
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-brand-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-brand-400/5 rounded-full blur-3xl pointer-events-none"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-badge bg-brand-500/10 border border-brand-500/30 text-brand-300 text-xs font-bold tracking-tight">
                    <span>🛍️</span>
                    <span>Demo Modul E-Commerce & WhatsApp Checkout</span>
                </div>
                
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight leading-tight sm:leading-tight">
                    Katalog Interaktif & WhatsApp Checkout
                </h1>
                
                <p class="text-slate-300 text-sm sm:text-base max-w-2xl mx-auto lg:mx-0 leading-relaxed">
                    Uji coba langsung pengalaman belanja online ringan: tambahkan produk ke keranjang reaktif (Alpine.js), lalu selesaikan pemesanan otomatis yang langsung terhubung ke WhatsApp Admin.
                </p>

                <div class="flex flex-wrap items-center justify-center lg:justify-start gap-3.5 pt-2">
                    <?= ui_button('Eksplorasi Katalog Produk', [
                        'variant' => 'primary',
                        'size'    => 'lg',
                        'href'    => '#katalog',
                        'icon'    => 'shopping-bag',
                    ]) ?>
                    
                    <?= ui_button('Uji Keranjang Belanja', [
                        'variant' => 'secondary',
                        'size'    => 'lg',
                        'href'    => 'javascript:void(0)',
                        'icon'    => 'shopping-cart',
                        'class'   => 'bg-slate-800 hover:bg-slate-700 text-white border-slate-700',
                        'attributes' => [
                            '@click' => '$store.cart.isOpen = true'
                        ]
                    ]) ?>
                </div>

                <!-- Features list -->
                <div class="pt-6 border-t border-slate-800/80 grid grid-cols-3 gap-4 text-center lg:text-left text-xs text-slate-300">
                    <div>
                        <p class="font-extrabold text-white text-base sm:text-lg tracking-tight">Reaktif</p>
                        <p class="text-[11px] text-slate-400">Keranjang Tanpa Reload</p>
                    </div>
                    <div>
                        <p class="font-extrabold text-white text-base sm:text-lg tracking-tight">Instan</p>
                        <p class="text-[11px] text-slate-400">Template Pesan WhatsApp</p>
                    </div>
                    <div>
                        <p class="font-extrabold text-white text-base sm:text-lg tracking-tight">Tercatat</p>
                        <p class="text-[11px] text-slate-400">Tersimpan ke Database</p>
                    </div>
                </div>
            </div>

            <!-- Hero Feature Image / Card -->
            <div class="lg:col-span-5 hidden lg:block">
                <div class="relative mx-auto max-w-md">
                    <div class="relative bg-slate-900/90 backdrop-blur-xl border border-slate-700/90 rounded-card p-6">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-rose-500/80"></div>
                                <div class="w-3 h-3 rounded-full bg-amber-500/80"></div>
                                <div class="w-3 h-3 rounded-full bg-emerald-500/80"></div>
                            </div>
                            <span class="text-xs font-mono text-slate-400">Alur Checkout WhatsApp</span>
                        </div>
                        <div class="mt-4 space-y-3">
                            <div class="flex gap-3 items-center bg-slate-800/70 p-3 rounded-btn border border-slate-700/60">
                                <div class="w-10 h-10 rounded-btn bg-brand-500/20 text-brand-300 flex items-center justify-center font-bold">
                                    🛒
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-bold text-white truncate">1. Pilih Produk & Keranjang</p>
                                    <p class="text-[11px] text-slate-400">Otomatis kalkulasi total belanja</p>
                                </div>
                            </div>
                            <div class="flex gap-3 items-center bg-slate-800/70 p-3 rounded-btn border border-slate-700/60">
                                <div class="w-10 h-10 rounded-btn bg-slate-700/40 text-slate-300 flex items-center justify-center font-bold">
                                    📝
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-bold text-white truncate">2. Isi Data & Alamat</p>
                                    <p class="text-[11px] text-slate-400">Nama, WhatsApp, alamat pengiriman</p>
                                </div>
                            </div>
                            <div class="flex gap-3 items-center bg-brand-950/60 p-3 rounded-btn border border-brand-700/40">
                                <div class="w-10 h-10 rounded-btn bg-brand-600 text-white flex items-center justify-center font-bold">
                                    💬
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-bold text-brand-200 truncate">3. Kirim Format Pesan ke WA</p>
                                    <p class="text-[11px] text-brand-300/80">Admin langsung merespon & memproses</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Main Catalog Container -->
<section id="katalog" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
    
    <!-- Section Header & Filter Controls -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 pb-8 border-b border-slate-200/80">
        <div>
            <span class="text-xs font-extrabold uppercase tracking-wider text-brand-600">Etalase Demo</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mt-1">
                Katalog Produk Pilihan
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Menampilkan <strong class="text-slate-800"><?= $totalProducts ?></strong> produk siap pesan via WhatsApp
            </p>
        </div>

        <!-- Filter & Sorting Form -->
        <form method="GET" action="<?= base_url('demo.php') ?>#katalog" class="flex flex-wrap items-center gap-3">
            <?php if ($categoryId > 0): ?>
                <input type="hidden" name="category" value="<?= $categoryId ?>">
            <?php endif; ?>
            
            <?php if (!empty($search)): ?>
                <input type="hidden" name="q" value="<?= sanitize($search) ?>">
            <?php endif; ?>

            <div class="flex items-center gap-2 bg-white px-3.5 py-2 rounded-btn border border-slate-200/80">
                <i class="ph ph-arrows-down-up text-slate-400 text-sm"></i>
                <label for="sort" class="text-xs font-medium text-slate-500">Urutkan:</label>
                <select name="sort" id="sort" onchange="this.form.submit()" class="text-xs font-semibold text-slate-800 bg-transparent focus:outline-none cursor-pointer">
                    <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Terbaru / Rekomendasi</option>
                    <option value="price_low" <?= $sort === 'price_low' ? 'selected' : '' ?>>Harga: Terendah</option>
                    <option value="price_high" <?= $sort === 'price_high' ? 'selected' : '' ?>>Harga: Tertinggi</option>
                    <option value="name_asc" <?= $sort === 'name_asc' ? 'selected' : '' ?>>Nama: A - Z</option>
                </select>
            </div>
        </form>
    </div>

    <!-- Category Tabs -->
    <div class="mt-6 flex items-center gap-2 overflow-x-auto pb-4 no-scrollbar">
        <a 
            href="<?= base_url('demo.php' . (!empty($search) ? '?q=' . urlencode($search) : '')) ?>#katalog" 
            class="flex-shrink-0 px-4 py-2 rounded-btn text-xs font-bold transition apple-tap <?= $categoryId === 0 ? 'bg-slate-900 text-white border border-slate-900' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200/80' ?>">
            Semua Kategori (<?= count($products) ?>)
        </a>
        <?php foreach ($categories as $cat): ?>
            <a 
                href="<?= base_url('demo.php?category=' . $cat['id'] . (!empty($search) ? '&q=' . urlencode($search) : '')) ?>#katalog" 
                class="flex-shrink-0 px-4 py-2 rounded-btn text-xs font-bold transition apple-tap <?= $categoryId == $cat['id'] ? 'bg-brand-600 text-white border border-brand-500/20' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200/80' ?>">
                <?= sanitize($cat['name']) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Active Search Filter Badge -->
    <?php if (!empty($search)): ?>
        <div class="mt-4 flex items-center justify-between p-3 rounded-card bg-brand-50 border border-brand-200/80 text-xs text-brand-800">
            <div class="flex items-center gap-2">
                <i class="ph ph-magnifying-glass text-brand-600 text-sm"></i>
                <span>Hasil pencarian untuk: <strong>"<?= sanitize($search) ?>"</strong></span>
            </div>
            <a href="<?= base_url('demo.php') ?>#katalog" class="text-brand-700 hover:text-brand-900 font-bold underline">Reset Pencarian</a>
        </div>
    <?php endif; ?>

    <!-- Products Grid (Zero Shadows, Flat Hairline Borders, Token Radiuses) -->
    <?php if (empty($products)): ?>
        <?= ui_empty_state(
            'Tidak Ada Produk Ditemukan',
            'Silakan coba kata kunci lain atau pilih kategori yang berbeda.',
            [
                'icon'       => 'package',
                'buttonText' => 'Lihat Semua Produk Demo',
                'buttonHref' => base_url('demo.php#katalog'),
                'buttonIcon' => 'shopping-bag'
            ]
        ) ?>
    <?php else: ?>
        <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php foreach ($products as $product): ?>
                <?= ui_product_card($product) ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</section>

<!-- Trust & Feature Section -->
<section class="bg-white border-y border-slate-200/80 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-card bg-brand-50 border border-brand-200/70 text-brand-600 flex items-center justify-center flex-shrink-0">
                    <i class="ph ph-shield-check text-2xl"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-900 tracking-tight">Produk 100% Teruji</h4>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">Semua produk demo dikelola secara real-time dari panel admin.</p>
                </div>
            </div>

            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-card bg-brand-50 border border-brand-200/70 text-brand-600 flex items-center justify-center flex-shrink-0">
                    <i class="ph ph-chat-teardrop-text text-2xl"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-900 tracking-tight">Pemesanan WhatsApp</h4>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">Format pesan otomatis dengan ringkasan pesanan & kode transaksi.</p>
                </div>
            </div>

            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-card bg-slate-100 border border-slate-200/80 text-slate-700 flex items-center justify-center flex-shrink-0">
                    <i class="ph ph-lightning text-2xl"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-900 tracking-tight">Keranjang Cepat</h4>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">Penyimpanan client-side Alpine.js tanpa jeda reload halaman.</p>
                </div>
            </div>

            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-card bg-amber-50 border border-amber-200/70 text-amber-600 flex items-center justify-center flex-shrink-0">
                    <i class="ph ph-database text-2xl"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-900 tracking-tight">Tersimpan ke Database</h4>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">Admin dapat mengelola data pesanan masuk melalui dashboard admin.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
