<?php
/**
 * Standalone E-Commerce Showcase Demo Application
 *
 * Distinct implementation showcasing real-world Vanilla PHP UI component primitives,
 * dynamic Alpine.js reactive cart, and instant WhatsApp order generation.
 */
$active_nav = 'demo';
$page_title = 'Showcase Demo Store - Vanilla PHP UI';
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
        error_log('Error loading demo products: ' . $e->getMessage());
    }
}

$settings = get_settings();
require_once __DIR__ . '/includes/header.php';
?>

<!-- Distinct Showcase App Header Banner -->
<div class="bg-gradient-to-r from-emerald-950 via-slate-900 to-slate-950 text-white border-b border-slate-800 py-3 px-4">
    <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
        <div class="flex items-center gap-2">
            <span class="px-2 py-0.5 rounded-badge bg-emerald-500 text-white font-semibold text-[10px]">LIVE DEMO STORE</span>
            <span class="text-slate-300 font-medium">Katalog Toko Online &amp; WhatsApp Checkout &bull; Siap di-scaffold ke folder baru</span>
        </div>
        <div class="flex items-center gap-3">
            <a href="<?= base_url('scaffold.php') ?>" class="text-emerald-400 hover:text-emerald-300 transition flex items-center gap-1 font-semibold">
                <i class="ph-bold ph-lightning"></i>
                <span>Scaffold / Install Toko Ini</span>
            </a>
            <span class="text-slate-600">•</span>
            <a href="<?= base_url('design-system.php') ?>" class="text-brand-300 hover:text-white transition flex items-center gap-1">
                <i class="ph ph-palette"></i>
                <span>Design System</span>
            </a>
            <span class="text-slate-600">•</span>
            <a href="<?= base_url() ?>" class="text-slate-400 hover:text-white transition flex items-center gap-1">
                <i class="ph ph-arrow-left"></i>
                <span>Kembali ke Overview</span>
            </a>
        </div>
    </div>
</div>

<!-- Demo Main Container -->
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <!-- Storefront Showcase Header & Quick Stats -->
    <div class="rounded-card bg-white border border-slate-200/80 p-6 sm:p-8 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div>
            <div class="flex items-center gap-2 mb-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Live Catalog Experience</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-semibold text-slate-900 tracking-tight">
                Apple Store Showcase & Cart
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-xl">
                Browse demo products, test instant filtering, add items to the reactive Alpine.js cart drawer, and simulate direct WhatsApp order processing.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <button 
                type="button" 
                @click="$store.cart.isOpen = true" 
                class="px-5 py-3 rounded-btn bg-brand-600 hover:bg-brand-700 text-white text-xs sm:text-sm font-semibold transition-all apple-tap flex items-center gap-2.5">
                <i class="ph ph-shopping-bag text-base"></i>
                <span>Open Cart (<span x-text="$store.cart.count">0</span> items)</span>
            </button>

            <?= ui_button('Token Switcher', [
                'variant' => 'secondary',
                'size'    => 'md',
                'href'    => base_url('design-system.php'),
                'icon'    => 'palette',
            ]) ?>
        </div>
    </div>

    <!-- Filter Toolbar (Apple Segmented Bar & Search) -->
    <div class="space-y-4">
        
        <!-- Category Filter Pills Bar -->
        <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
            <a href="<?= base_url('demo.php?' . http_build_query(array_merge($_GET, ['category' => 0]))) ?>" 
               class="px-4 py-2 rounded-btn text-xs font-semibold whitespace-nowrap transition-all apple-tap <?= ($categoryId === 0) ? 'bg-brand-600 text-white border border-brand-500/20' : 'bg-white hover:bg-slate-100 text-slate-700 border border-slate-200/80' ?>">
                All Categories (<?= $totalProducts ?>)
            </a>

            <?php foreach ($categories as $cat): ?>
                <a href="<?= base_url('demo.php?' . http_build_query(array_merge($_GET, ['category' => $cat['id']]))) ?>" 
                   class="px-4 py-2 rounded-btn text-xs font-semibold whitespace-nowrap transition-all apple-tap <?= ($categoryId === (int)$cat['id']) ? 'bg-brand-600 text-white border border-brand-500/20' : 'bg-white hover:bg-slate-100 text-slate-700 border border-slate-200/80' ?>">
                    <?= sanitize($cat['name']) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Search & Sort Row -->
        <div class="bg-white rounded-card border border-slate-200/80 p-4 flex flex-col sm:flex-row items-center justify-between gap-4">
            
            <!-- Search Query Form -->
            <form action="<?= base_url('demo.php') ?>" method="GET" class="w-full sm:w-80 relative">
                <?php if ($categoryId > 0): ?>
                    <input type="hidden" name="category" value="<?= $categoryId ?>">
                <?php endif; ?>
                <input 
                    type="text" 
                    name="q" 
                    value="<?= sanitize($search) ?>" 
                    placeholder="Search demo products..." 
                    class="w-full pl-10 pr-10 py-2 text-xs rounded-input bg-slate-50 border border-slate-200/80 focus:border-brand-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-all">
                <i class="ph ph-magnifying-glass text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 text-sm pointer-events-none"></i>
                
                <?php if (!empty($search)): ?>
                    <a href="<?= base_url('demo.php?' . http_build_query(['category' => $categoryId])) ?>" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1">
                        <i class="ph ph-x text-xs"></i>
                    </a>
                <?php endif; ?>
            </form>

            <!-- Sort Dropdown Form -->
            <form action="<?= base_url('demo.php') ?>" method="GET" class="w-full sm:w-auto flex items-center gap-2">
                <?php if ($categoryId > 0): ?>
                    <input type="hidden" name="category" value="<?= $categoryId ?>">
                <?php endif; ?>
                <?php if (!empty($search)): ?>
                    <input type="hidden" name="q" value="<?= sanitize($search) ?>">
                <?php endif; ?>

                <label for="sort-select" class="text-xs font-semibold text-slate-500 whitespace-nowrap hidden sm:inline">Sort by:</label>
                <select 
                    id="sort-select"
                    name="sort" 
                    onchange="this.form.submit()" 
                    class="w-full sm:w-auto px-3.5 py-2 text-xs rounded-input bg-slate-50 border border-slate-200/80 text-slate-800 font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500/20 cursor-pointer">
                    <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>✨ Featured & Newest</option>
                    <option value="price_low" <?= $sort === 'price_low' ? 'selected' : '' ?>>💲 Price: Low to High</option>
                    <option value="price_high" <?= $sort === 'price_high' ? 'selected' : '' ?>>💎 Price: High to Low</option>
                    <option value="name_asc" <?= $sort === 'name_asc' ? 'selected' : '' ?>>🔤 Alphabetical (A-Z)</option>
                </select>
            </form>

        </div>
    </div>

    <!-- Active Filter Chips -->
    <?php if ($categoryId > 0 || !empty($search)): ?>
        <div class="flex items-center gap-2 flex-wrap text-xs">
            <span class="text-slate-400 font-medium">Active filters:</span>
            <?php if (!empty($search)): ?>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-200 text-slate-800 font-semibold">
                    <span>Query: "<?= sanitize($search) ?>"</span>
                    <a href="<?= base_url('demo.php?' . http_build_query(['category' => $categoryId, 'sort' => $sort])) ?>" class="hover:text-rose-600"><i class="ph ph-x"></i></a>
                </span>
            <?php endif; ?>

            <?php if ($categoryId > 0): 
                $activeCatName = '';
                foreach ($categories as $c) {
                    if ((int)$c['id'] === $categoryId) {
                        $activeCatName = $c['name'];
                        break;
                    }
                }
            ?>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-brand-50 text-brand-700 border border-brand-200 font-semibold">
                    <span>Category: <?= sanitize($activeCatName) ?></span>
                    <a href="<?= base_url('demo.php?' . http_build_query(['q' => $search, 'sort' => $sort])) ?>" class="hover:text-rose-600"><i class="ph ph-x"></i></a>
                </span>
            <?php endif; ?>

            <a href="<?= base_url('demo.php') ?>" class="text-rose-600 hover:underline font-semibold ml-1">Reset All</a>
        </div>
    <?php endif; ?>

    <!-- Product Grid -->
    <?php if (!empty($products)): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($products as $product): ?>
                <?= ui_product_card($product) ?>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="py-12">
            <?= ui_empty_state('No matching products found', 'Try adjusting your search query or selecting a different category.', [
                'icon'       => 'magnifying-glass',
                'buttonText' => 'View All Products',
                'buttonHref' => base_url('demo.php'),
            ]) ?>
        </div>
    <?php endif; ?>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
