<?php
/**
 * Modern Lightweight Native PHP Website - Homepage & Showcase
 */
$active_nav = 'home';
$page_title = null; // Use default from settings
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/helpers/format.php';

$db = getDB();
$spotlightProducts = [];
$totalProducts = 0;
$totalCategories = 0;

if ($db) {
    try {
        // Fetch count of categories & products
        $catCountStmt = $db->query("SELECT COUNT(*) FROM categories WHERE is_active = 1");
        $totalCategories = (int)$catCountStmt->fetchColumn();

        $prodCountStmt = $db->query("SELECT COUNT(*) FROM products WHERE is_active = 1");
        $totalProducts = (int)$prodCountStmt->fetchColumn();

        // Fetch 4 featured / spotlight products for preview
        $stmt = $db->query("SELECT p.*, c.name as category_name 
                            FROM products p 
                            LEFT JOIN categories c ON p.category_id = c.id 
                            WHERE p.is_active = 1 
                            ORDER BY p.is_featured DESC, p.created_at DESC 
                            LIMIT 4");
        $spotlightProducts = $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log('Error loading homepage data: ' . $e->getMessage());
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Section (Lightweight, Modern, Apple Tactile & Material Design) -->
<section class="relative bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 text-white overflow-hidden py-20 lg:py-28 border-b border-slate-800">
    <!-- Subtle backdrop accent blur -->
    <div class="absolute -top-32 -right-32 w-[500px] h-[500px] bg-brand-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-32 -left-32 w-[500px] h-[500px] bg-brand-400/5 rounded-full blur-3xl pointer-events-none"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-badge bg-brand-500/10 border border-brand-500/30 text-brand-300 text-xs font-bold tracking-tight">
                    <span class="w-2 h-2 rounded-full bg-brand-400 animate-pulse"></span>
                    <span>⚡ Lightweight Native PHP Web Starter & Application Kit</span>
                </div>
                
                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-[1.15] sm:leading-[1.15]">
                    Website Cepat, Modern & <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-300 to-brand-500">Bebas Bloatware</span>
                </h1>
                
                <p class="text-slate-300 text-sm sm:text-base lg:text-lg max-w-2xl mx-auto lg:mx-0 leading-relaxed">
                    Dibangun dengan arsitektur **Pure Native PHP 8.x**, **Tailwind CSS**, **Alpine.js**, dan **MySQL**. Super cepat, hemat memori RAM, dan 100% kompatibel dengan cPanel / shared hosting hingga VPS.
                </p>

                <div class="flex flex-wrap items-center justify-center lg:justify-start gap-3.5 pt-3">
                    <?= ui_button('Lihat Demo E-Commerce & WA', [
                        'variant' => 'primary',
                        'size'    => 'lg',
                        'href'    => base_url('demo.php'),
                        'icon'    => 'shopping-bag',
                    ]) ?>
                    
                    <?= ui_button('Eksplorasi Design System', [
                        'variant' => 'secondary',
                        'size'    => 'lg',
                        'href'    => base_url('admin/design-system.php'),
                        'icon'    => 'sparkle',
                        'class'   => 'bg-slate-800 hover:bg-slate-700 text-white border-slate-700',
                    ]) ?>

                    <?= ui_button('Panel Admin', [
                        'variant' => 'ghost',
                        'size'    => 'lg',
                        'href'    => base_url('admin/login.php'),
                        'icon'    => 'shield-check',
                        'class'   => 'text-slate-300 hover:text-white hover:bg-slate-800/60 border border-slate-700/80',
                    ]) ?>
                </div>

                <!-- Key Architecture Metrics -->
                <div class="pt-8 border-t border-slate-800/80 grid grid-cols-2 sm:grid-cols-4 gap-4 text-center lg:text-left text-xs text-slate-300">
                    <div>
                        <p class="font-extrabold text-white text-lg sm:text-xl tracking-tight">< 50ms</p>
                        <p class="text-[11px] text-slate-400">Response Time (TTFB)</p>
                    </div>
                    <div>
                        <p class="font-extrabold text-white text-lg sm:text-xl tracking-tight">0 MB</p>
                        <p class="text-[11px] text-slate-400">Vendor Bloat (Pure PHP)</p>
                    </div>
                    <div>
                        <p class="font-extrabold text-white text-lg sm:text-xl tracking-tight">100%</p>
                        <p class="text-[11px] text-slate-400">Shared Hosting Ready</p>
                    </div>
                    <div>
                        <p class="font-extrabold text-white text-lg sm:text-xl tracking-tight">8 Palet</p>
                        <p class="text-[11px] text-slate-400">Dynamic Theme Engine</p>
                    </div>
                </div>
            </div>

            <!-- Hero Feature Preview Card -->
            <div class="lg:col-span-5 hidden lg:block">
                <div class="relative mx-auto max-w-md">
                    <!-- Glass Card -->
                    <div class="relative bg-slate-900/90 backdrop-blur-xl border border-slate-700/90 rounded-card p-6 shadow-none">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-rose-500/80"></div>
                                <div class="w-3 h-3 rounded-full bg-amber-500/80"></div>
                                <div class="w-3 h-3 rounded-full bg-emerald-500/80"></div>
                            </div>
                            <span class="text-xs font-mono text-brand-300">Native-PHP / Starter Stack</span>
                        </div>
                        
                        <div class="mt-4 space-y-3">
                            <div class="flex gap-3 items-center bg-slate-800/80 p-3.5 rounded-btn border border-slate-700/60">
                                <div class="w-9 h-9 rounded-btn bg-brand-500/20 text-brand-300 flex items-center justify-center font-bold text-sm">
                                    <i class="ph ph-shopping-cart"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <p class="text-xs font-bold text-white">E-Commerce & WhatsApp Demo</p>
                                        <span class="text-[10px] px-1.5 py-0.5 rounded-badge bg-emerald-500/20 text-emerald-300 font-bold">Siap Pakai</span>
                                    </div>
                                    <p class="text-[11px] text-slate-400">Katalog, Drawer Cart, Auto Order WA</p>
                                </div>
                            </div>

                            <div class="flex gap-3 items-center bg-slate-800/80 p-3.5 rounded-btn border border-slate-700/60">
                                <div class="w-9 h-9 rounded-btn bg-blue-500/20 text-blue-300 flex items-center justify-center font-bold text-sm">
                                    <i class="ph ph-squares-four"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-bold text-white">Modular UI Primitives</p>
                                    <p class="text-[11px] text-slate-400">12+ Komponen PHP ala shadcn</p>
                                </div>
                            </div>

                            <div class="flex gap-3 items-center bg-slate-800/80 p-3.5 rounded-btn border border-slate-700/60">
                                <div class="w-9 h-9 rounded-btn bg-purple-500/20 text-purple-300 flex items-center justify-center font-bold text-sm">
                                    <i class="ph ph-palette"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-bold text-white">Theme & Radius Engine</p>
                                    <p class="text-[11px] text-slate-400">8 Warna Primer + 6 Corner Radius</p>
                                </div>
                            </div>

                            <div class="flex gap-3 items-center bg-slate-800/80 p-3.5 rounded-btn border border-slate-700/60">
                                <div class="w-9 h-9 rounded-btn bg-amber-500/20 text-amber-300 flex items-center justify-center font-bold text-sm">
                                    <i class="ph ph-lock-key"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-bold text-white">Admin CRUD & Keamanan</p>
                                    <p class="text-[11px] text-slate-400">PDO Prepared Statements, CSRF, Bcrypt</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 pt-4 border-t border-slate-800">
                            <a href="<?= base_url('demo.php') ?>" class="w-full flex items-center justify-center gap-2 py-2 px-3 bg-brand-600 hover:bg-brand-500 text-white rounded-btn text-xs font-bold transition apple-tap">
                                <span>Buka Halaman Demo Interaktif</span>
                                <i class="ph ph-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Core Pillars & Features Section -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
    <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
        <span class="text-xs font-extrabold uppercase tracking-wider text-brand-600">Arsitektur & Keunggulan</span>
        <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
            Dirancang untuk Kecepatan, Kesederhanaan & Fleksibilitas
        </h2>
        <p class="text-sm sm:text-base text-slate-600 leading-relaxed">
            Semua yang Anda butuhkan untuk membangun website modern tanpa kompleksitas build-step atau server requirements yang tinggi.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        
        <!-- Feature 1 -->
        <div class="bg-white p-8 rounded-card border border-slate-200/80 hover:border-brand-300 transition group">
            <?= ui_icon_box('lightning', 'brand', ['size' => 'lg', 'class' => 'mb-5 group-hover:scale-110 transition-transform']) ?>
            <h3 class="text-lg font-bold text-slate-900 mb-2 tracking-tight">Pure Native PHP 8.x</h3>
            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                Tanpa framework berat yang memperlambat TTFB. Sangat ringan, hemat memori RAM, dan berjalan optimal di shared hosting murah sekalipun.
            </p>
        </div>

        <!-- Feature 2 -->
        <div class="bg-white p-8 rounded-card border border-slate-200/80 hover:border-brand-300 transition group">
            <?= ui_icon_box('shopping-cart', 'brand', ['size' => 'lg', 'class' => 'mb-5 group-hover:scale-110 transition-transform']) ?>
            <h3 class="text-lg font-bold text-slate-900 mb-2 tracking-tight">Demo E-Commerce & WhatsApp</h3>
            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                Dilengkapi showcase katalog produk, keranjang drawer reaktif, dan checkout WhatsApp otomatis yang sudah terintegrasi ke database.
            </p>
        </div>

        <!-- Feature 3 -->
        <div class="bg-white p-8 rounded-card border border-slate-200/80 hover:border-brand-300 transition group">
            <?= ui_icon_box('squares-four', 'brand', ['size' => 'lg', 'class' => 'mb-5 group-hover:scale-110 transition-transform']) ?>
            <h3 class="text-lg font-bold text-slate-900 mb-2 tracking-tight">Komponen UI ala shadcn</h3>
            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                Koleksi fungsi komponen primitif reusable (`ui_button`, `ui_card`, `ui_input`, `ui_badge`, dsb.) untuk penulisan kode yang rapi.
            </p>
        </div>

        <!-- Feature 4 -->
        <div class="bg-white p-8 rounded-card border border-slate-200/80 hover:border-brand-300 transition group">
            <?= ui_icon_box('palette', 'brand', ['size' => 'lg', 'class' => 'mb-5 group-hover:scale-110 transition-transform']) ?>
            <h3 class="text-lg font-bold text-slate-900 mb-2 tracking-tight">Dynamic Theme Customizer</h3>
            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                Tersedia 8 palet warna elegan dan 6 pilihan corner radius yang dapat diubah instan melalui konfigurasi PHP atau panel admin.
            </p>
        </div>

        <!-- Feature 5 -->
        <div class="bg-white p-8 rounded-card border border-slate-200/80 hover:border-brand-300 transition group">
            <?= ui_icon_box('shield-check', 'brand', ['size' => 'lg', 'class' => 'mb-5 group-hover:scale-110 transition-transform']) ?>
            <h3 class="text-lg font-bold text-slate-900 mb-2 tracking-tight">Keamanan Terpadu</h3>
            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                Kueri database menggunakan PDO Prepared Statements, hashing password menggunakan Bcrypt, serta proteksi CSRF pada semua form.
            </p>
        </div>

        <!-- Feature 6 -->
        <div class="bg-white p-8 rounded-card border border-slate-200/80 hover:border-brand-300 transition group">
            <?= ui_icon_box('gauge', 'brand', ['size' => 'lg', 'class' => 'mb-5 group-hover:scale-110 transition-transform']) ?>
            <h3 class="text-lg font-bold text-slate-900 mb-2 tracking-tight">Dashboard Admin Lengkap</h3>
            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                Panel manajemen konten siap pakai untuk mengelola produk, kategori, riwayat pesanan, profil bisnis, dan kustomisasi tema visual.
            </p>
        </div>

    </div>
</section>

<!-- Interactive Feature Showcase Cards (Live Modules) -->
<section class="bg-slate-100/70 border-y border-slate-200/80 py-16 lg:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
        
        <!-- Module Showcase 1: E-Commerce Demo -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center bg-white p-8 sm:p-10 rounded-card border border-slate-200/80">
            <div class="lg:col-span-7 space-y-5">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-badge bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-bold">
                    <span>🛍️ Live Demo Fitur</span>
                </div>
                <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                    Modul Toko Online & Checkout WhatsApp
                </h3>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                    Fitur katalog interaktif dengan filter kategori, pencarian real-time, shopping cart drawer reaktif tanpa reload, dan pembuatan template checkout WhatsApp otomatis yang tercatat ke database.
                </p>
                <div class="pt-2 flex flex-wrap items-center gap-3">
                    <?= ui_button('Buka Demo Katalog & WA', [
                        'variant' => 'primary',
                        'size'    => 'md',
                        'href'    => base_url('demo.php'),
                        'icon'    => 'arrow-square-out',
                    ]) ?>
                    <?= ui_button('Uji Keranjang Belanja', [
                        'variant' => 'secondary',
                        'size'    => 'md',
                        'href'    => 'javascript:void(0)',
                        'icon'    => 'shopping-cart',
                        'attributes' => [
                            '@click' => '$store.cart.isOpen = true'
                        ]
                    ]) ?>
                </div>
            </div>
            
            <div class="lg:col-span-5 bg-slate-900 text-white p-6 rounded-card border border-slate-800 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-800 text-xs text-slate-400">
                    <span class="font-mono font-bold text-white">Alur Transaksi Cepat</span>
                    <span class="text-brand-400">Direct WhatsApp</span>
                </div>
                <div class="space-y-2.5 text-xs text-slate-300">
                    <div class="p-2.5 bg-slate-800/80 rounded-btn border border-slate-700/60 flex items-center gap-2.5">
                        <span class="w-6 h-6 rounded-full bg-brand-600 text-white flex items-center justify-center font-bold text-[11px]">1</span>
                        <span>Pelanggan pilih produk & jumlah pesanan</span>
                    </div>
                    <div class="p-2.5 bg-slate-800/80 rounded-btn border border-slate-700/60 flex items-center gap-2.5">
                        <span class="w-6 h-6 rounded-full bg-brand-600 text-white flex items-center justify-center font-bold text-[11px]">2</span>
                        <span>Isi data pengiriman pada form checkout</span>
                    </div>
                    <div class="p-2.5 bg-slate-800/80 rounded-btn border border-slate-700/60 flex items-center gap-2.5">
                        <span class="w-6 h-6 rounded-full bg-brand-600 text-white flex items-center justify-center font-bold text-[11px]">3</span>
                        <span>Pesan WhatsApp terkirim ke CS & order tersimpan</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Module Showcase 2: Design System & Admin -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Card Design System -->
            <div class="bg-white p-8 rounded-card border border-slate-200/80 space-y-4">
                <?= ui_icon_box('sparkle', 'brand', ['size' => 'md']) ?>
                <h3 class="text-xl font-bold text-slate-900 tracking-tight">Showcase & Living Design System</h3>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                    Dokumentasi hidup interaktif yang menampilkan semua variasi tombol, input form, badge, kartu metrik, banner notifikasi, dan avatar box.
                </p>
                <div class="pt-2">
                    <?= ui_button('Buka Showcase Komponen', [
                        'variant' => 'secondary',
                        'size'    => 'sm',
                        'href'    => base_url('admin/design-system.php'),
                        'icon'    => 'arrow-right',
                    ]) ?>
                </div>
            </div>

            <!-- Card Admin Panel -->
            <div class="bg-white p-8 rounded-card border border-slate-200/80 space-y-4">
                <?= ui_icon_box('sliders-horizontal', 'brand', ['size' => 'md']) ?>
                <h3 class="text-xl font-bold text-slate-900 tracking-tight">Panel Admin & Manajemen Lengkap</h3>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                    Kelola data produk, kategori taksonomi, pantau pesanan masuk, ubah profil bisnis, serta atur palet warna website secara langsung.
                </p>
                <div class="pt-2">
                    <?= ui_button('Login Dashboard Admin', [
                        'variant' => 'secondary',
                        'size'    => 'sm',
                        'href'    => base_url('admin/login.php'),
                        'icon'    => 'shield-check',
                    ]) ?>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- Spotlight Products Preview Section (Optional sneak peek to Demo) -->
<?php if (!empty($spotlightProducts)): ?>
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10">
        <div>
            <span class="text-xs font-extrabold uppercase tracking-wider text-brand-600">Cuplikan Demo</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mt-1">
                Katalog Produk Unggulan
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Sampel produk dari database yang siap dicoba di halaman demo e-commerce.
            </p>
        </div>
        <div>
            <?= ui_button('Buka Semua Katalog Demo', [
                'variant' => 'primary',
                'size'    => 'md',
                'href'    => base_url('demo.php#katalog'),
                'icon'    => 'shopping-bag',
            ]) ?>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php foreach ($spotlightProducts as $product): ?>
            <?= ui_product_card($product) ?>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- Call To Action (CTA) Section -->
<section class="bg-slate-900 text-white py-16 border-t border-slate-800">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
        <span class="inline-block px-3 py-1 rounded-badge bg-brand-500/10 border border-brand-500/30 text-brand-300 text-xs font-bold tracking-tight">
            🚀 Siap Dikembangkan & Disesuaikan
        </span>
        <h2 class="text-2xl sm:text-4xl font-extrabold tracking-tight">
            Mulai Kembangkan Website Anda Sekarang
        </h2>
        <p class="text-slate-300 text-xs sm:text-sm max-w-2xl mx-auto leading-relaxed">
            Struktur kode bersih, terdokumentasi rapi, dan mudah dimodifikasi untuk profil bisnis, landing page portofolio, maupun etalase toko daring.
        </p>
        <div class="flex flex-wrap items-center justify-center gap-3 pt-2">
            <?= ui_button('Coba Demo E-Commerce', [
                'variant' => 'primary',
                'size'    => 'lg',
                'href'    => base_url('demo.php'),
                'icon'    => 'shopping-bag',
            ]) ?>
            <?= ui_button('Tentang Kami', [
                'variant' => 'secondary',
                'size'    => 'lg',
                'href'    => base_url('about.php'),
                'icon'    => 'buildings',
                'class'   => 'bg-slate-800 hover:bg-slate-700 text-white border-slate-700',
            ]) ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
