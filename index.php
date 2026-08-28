<?php
/**
 * Modern Lightweight Native PHP UI Component Library & Showcase Platform
 */
$active_nav = 'home';
$page_title = 'NativePHP UI - Apple-inspired Component Library & Starter Stack';
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/helpers/format.php';

$db = getDB();
$spotlightProducts = [];
$totalProducts = 0;
$totalCategories = 0;

if ($db) {
    try {
        $catCountStmt = $db->query("SELECT COUNT(*) FROM categories WHERE is_active = 1");
        $totalCategories = (int)$catCountStmt->fetchColumn();

        $prodCountStmt = $db->query("SELECT COUNT(*) FROM products WHERE is_active = 1");
        $totalProducts = (int)$prodCountStmt->fetchColumn();

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

<!-- Hero Section (Apple Tactile, Translucent Material & Fluid Motion) -->
<section class="relative bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 text-white overflow-hidden py-20 lg:py-28 border-b border-slate-800">
    <div class="absolute -top-32 -right-32 w-[550px] h-[550px] bg-brand-500/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-32 -left-32 w-[550px] h-[550px] bg-brand-400/10 rounded-full blur-3xl pointer-events-none"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <!-- Left Hero Content -->
            <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-badge bg-brand-500/10 border border-brand-500/30 text-brand-300 text-xs font-semibold tracking-tight">
                    <span class="w-2 h-2 rounded-full bg-brand-400 animate-pulse"></span>
                    <span>⚡ Pure Native PHP UI Component Library & Architecture</span>
                </div>
                
                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-semibold tracking-tight leading-[1.12]">
                    Fluid, Elegant UI with <br/>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-300 to-brand-500">Pure Native PHP</span>
                </h1>
                
                <p class="text-slate-300 text-sm sm:text-base lg:text-lg max-w-2xl mx-auto lg:mx-0 leading-relaxed">
                    A lightweight, modular UI component library and application starter built on <strong>Apple Human Interface</strong> principles, <strong>Tailwind CSS</strong>, <strong>Alpine.js</strong>, and <strong>MySQL</strong>. Sub-50ms execution, 0 MB vendor bloat, zero drop-shadows, and 100% hosting compatibility.
                </p>

                <div class="flex flex-wrap items-center justify-center lg:justify-start gap-3.5 pt-3">
                    <?= ui_button('Explore Design Tokens', [
                        'variant' => 'primary',
                        'size'    => 'lg',
                        'href'    => base_url('design-system.php'),
                        'icon'    => 'palette',
                    ]) ?>
                    
                    <?= ui_button('Open Showcase Demo', [
                        'variant' => 'glass',
                        'size'    => 'lg',
                        'href'    => base_url('demo.php'),
                        'icon'    => 'shopping-bag',
                        'class'   => 'bg-slate-800/80 hover:bg-slate-700 text-white border-slate-700',
                    ]) ?>

                    <?= ui_button('Admin Panel', [
                        'variant' => 'ghost',
                        'size'    => 'lg',
                        'href'    => base_url('admin/login.php'),
                        'icon'    => 'shield-check',
                        'class'   => 'text-slate-300 hover:text-white hover:bg-slate-800/60 border border-slate-800',
                    ]) ?>
                </div>

                <!-- Key Architecture Metrics -->
                <div class="pt-8 border-t border-slate-800/80 grid grid-cols-2 sm:grid-cols-4 gap-4 text-center lg:text-left text-xs text-slate-300">
                    <div>
                        <p class="font-semibold text-white text-lg sm:text-xl tracking-tight">&lt; 50ms</p>
                        <p class="text-[11px] text-slate-400">Response Time (TTFB)</p>
                    </div>
                    <div>
                        <p class="font-semibold text-white text-lg sm:text-xl tracking-tight">0 MB</p>
                        <p class="text-[11px] text-slate-400">Vendor Bloat (Pure PHP)</p>
                    </div>
                    <div>
                        <p class="font-semibold text-white text-lg sm:text-xl tracking-tight">100%</p>
                        <p class="text-[11px] text-slate-400">Shared Hosting Ready</p>
                    </div>
                    <div>
                        <p class="font-semibold text-white text-lg sm:text-xl tracking-tight">8 Palettes</p>
                        <p class="text-[11px] text-slate-400">Dynamic Token Engine</p>
                    </div>
                </div>
            </div>

            <!-- Right Hero: Live Interactive Apple Widget Preview -->
            <div class="lg:col-span-5 hidden lg:block" x-data="{ sampleToggle: true, sampleCount: 1 }">
                <div class="relative mx-auto max-w-md">
                    
                    <!-- Glass Card Frame -->
                    <div class="relative bg-slate-900/90 backdrop-blur-2xl border border-slate-700/80 rounded-card p-6 shadow-none space-y-5">
                        
                        <!-- Header bar -->
                        <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-rose-500/80"></div>
                                <div class="w-3 h-3 rounded-full bg-amber-500/80"></div>
                                <div class="w-3 h-3 rounded-full bg-emerald-500/80"></div>
                            </div>
                            <span class="text-xs font-mono text-brand-300 flex items-center gap-1">
                                <i class="ph ph-sparkle"></i> Live Primitive Sandbox
                            </span>
                        </div>

                        <!-- Live Interactive Primitives -->
                        <div class="space-y-4">
                            <!-- Live Component Row 1: Badges -->
                            <div class="flex items-center justify-between bg-slate-800/60 p-3 rounded-btn border border-slate-700/60">
                                <span class="text-xs text-slate-300 font-medium">Status Primitive</span>
                                <?= ui_badge('Online & Active', 'brand', ['dot' => true, 'pulse' => true, 'class' => 'bg-brand-950/80 text-brand-300 border-brand-800']) ?>
                            </div>

                            <!-- Live Component Row 2: Tactile Counter -->
                            <div class="flex items-center justify-between bg-slate-800/60 p-3 rounded-btn border border-slate-700/60">
                                <div>
                                    <span class="text-xs text-slate-300 font-semibold block">Tactile Stepper</span>
                                    <span class="text-[10px] text-slate-400">Pointer-down feedback</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button 
                                        type="button" 
                                        @click="sampleCount = Math.max(1, sampleCount - 1)" 
                                        class="w-7 h-7 rounded-btn bg-slate-700 hover:bg-slate-600 text-white flex items-center justify-center text-xs font-semibold transition apple-tap">
                                        -
                                    </button>
                                    <span class="w-8 text-center text-sm font-semibold text-white font-mono" x-text="sampleCount">1</span>
                                    <button 
                                        type="button" 
                                        @click="sampleCount++" 
                                        class="w-7 h-7 rounded-btn bg-brand-600 hover:bg-brand-500 text-white flex items-center justify-center text-xs font-semibold transition apple-tap">
                                        +
                                    </button>
                                </div>
                            </div>

                            <!-- Live Component Row 3: iOS Switch -->
                            <div class="flex items-center justify-between bg-slate-800/60 p-3 rounded-btn border border-slate-700/60">
                                <div>
                                    <span class="text-xs text-slate-300 font-semibold block">Spring Toggle</span>
                                    <span class="text-[10px] text-slate-400">iOS fluid physics</span>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer select-none">
                                    <input type="checkbox" x-model="sampleToggle" class="sr-only peer">
                                    <div class="w-10 h-5 bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-brand-500 apple-tap"></div>
                                </label>
                            </div>

                            <!-- Code Snippet -->
                            <div class="p-3 rounded-btn bg-slate-950 border border-slate-800 text-[11px] font-mono text-slate-300 overflow-x-auto">
                                <span class="text-slate-500">&lt;?=</span> <span class="text-brand-300">ui_button</span>(<span class="text-emerald-300">'Confirm'</span>, [<span class="text-amber-300">'variant'</span> =&gt; <span class="text-emerald-300">'primary'</span>]) <span class="text-slate-500">?&gt;</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Section: Live Token Explorer Strip (Interactive Switcher) -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="p-6 rounded-card bg-white border border-slate-200/80 flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="space-y-1 text-center md:text-left">
            <span class="text-[10px] font-semibold uppercase tracking-wider text-brand-600 block">Dynamic Design Tokens</span>
            <h2 class="text-lg font-semibold text-slate-900 tracking-tight">Test 8 Color Palettes & 6 Corner Radius Scales</h2>
            <p class="text-xs text-slate-500">Switch palettes live to see the entire framework adapt in real-time.</p>
        </div>
        <div class="flex flex-wrap items-center justify-center gap-2">
            <a href="<?= base_url('design-system.php') ?>" class="px-4 py-2 rounded-btn bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold transition apple-tap flex items-center gap-1.5">
                <i class="ph ph-palette text-sm"></i>
                <span>Open Token Explorer</span>
            </a>
            <a href="<?= base_url('components.php') ?>" class="px-4 py-2 rounded-btn bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-semibold border border-slate-200/80 transition apple-tap flex items-center gap-1.5">
                <i class="ph ph-squares-four text-sm"></i>
                <span>View Component Primitives</span>
            </a>
        </div>
    </div>
</section>

<!-- Section: Apple Design Principles (Why Native PHP UI) -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    <div class="text-center max-w-2xl mx-auto">
        <span class="text-xs font-semibold uppercase tracking-wider text-brand-600 block">Design Foundations</span>
        <h2 class="text-2xl sm:text-3xl font-semibold text-slate-900 tracking-tight mt-1">Built with Apple Design Principles</h2>
        <p class="text-xs sm:text-sm text-slate-500 mt-2 leading-relaxed">
            Translating WWDC fluid interface principles directly into lightweight PHP primitives.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Pillar 1: Latency Kill -->
        <div class="p-6 rounded-card bg-white border border-slate-200/80 hover:border-brand-300 transition space-y-3">
            <?= ui_icon_box('hand-pointing', 'brand', ['size' => 'md']) ?>
            <h3 class="text-sm font-semibold text-slate-900 tracking-tight">Pointer-Down Response</h3>
            <p class="text-xs text-slate-500 leading-relaxed">
                Feedback lives on pointer-down with active scaling (<code class="font-mono text-brand-700">.apple-tap</code>), not after mouse release. Latency is eliminated.
            </p>
        </div>

        <!-- Pillar 2: Materials & Depth -->
        <div class="p-6 rounded-card bg-white border border-slate-200/80 hover:border-brand-300 transition space-y-3">
            <?= ui_icon_box('drop', 'brand', ['size' => 'md']) ?>
            <h3 class="text-sm font-semibold text-slate-900 tracking-tight">Translucent Materials</h3>
            <p class="text-xs text-slate-500 leading-relaxed">
                Navbars, toolbars, and sheets float with <code class="font-mono text-brand-700">backdrop-filter</code> blur and light-catching hairline borders.
            </p>
        </div>

        <!-- Pillar 3: Zero Shadows Policy -->
        <div class="p-6 rounded-card bg-white border border-slate-200/80 hover:border-brand-300 transition space-y-3">
            <?= ui_icon_box('square-split-horizontal', 'brand', ['size' => 'md']) ?>
            <h3 class="text-sm font-semibold text-slate-900 tracking-tight">Zero Shadow Flat Borders</h3>
            <p class="text-xs text-slate-500 leading-relaxed">
                No muddy box-shadows. Clean structural hierarchy is achieved through crisp 1px borders, subtle tint layers, and typography weight.
            </p>
        </div>

        <!-- Pillar 4: Optical Typography -->
        <div class="p-6 rounded-card bg-white border border-slate-200/80 hover:border-brand-300 transition space-y-3">
            <?= ui_icon_box('text-aa', 'brand', ['size' => 'md']) ?>
            <h3 class="text-sm font-semibold text-slate-900 tracking-tight">Optical Typography</h3>
            <p class="text-xs text-slate-500 leading-relaxed">
                Size-specific tracking (<code class="font-mono text-brand-700">letter-spacing: -0.02em</code> on display headlines) and comfortable body copy leading.
            </p>
        </div>

    </div>
</section>

<!-- Section: Showcase Demo Spotlight -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 border-b border-slate-200/80 pb-6">
        <div>
            <span class="text-xs font-semibold uppercase tracking-wider text-brand-600 block">Showcase Application</span>
            <h2 class="text-2xl font-semibold text-slate-900 tracking-tight mt-1">E-Commerce & WhatsApp Demo Store</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                A production-ready e-commerce sample built entirely using our UI primitives.
            </p>
        </div>
        <div>
            <?= ui_button('Open Full Catalog & Cart', [
                'variant'   => 'primary',
                'size'      => 'sm',
                'href'      => base_url('demo.php'),
                'iconRight' => 'caret-right',
            ]) ?>
        </div>
    </div>

    <!-- Product Grid Spotlight -->
    <?php if (!empty($spotlightProducts)): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($spotlightProducts as $prod): ?>
                <?= ui_product_card($prod) ?>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <?= ui_empty_state('No products found', 'Run the database installer to seed demo products.', [
            'buttonText' => 'Open Demo Catalog',
            'buttonHref' => base_url('demo.php'),
        ]) ?>
    <?php endif; ?>
</section>

<!-- Section: Quick Code Implementation -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="rounded-card bg-slate-950 text-white border border-slate-800 p-8 sm:p-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
            <div class="space-y-4">
                <span class="text-xs font-semibold uppercase tracking-wider text-brand-400 block">Developer Experience</span>
                <h2 class="text-2xl sm:text-3xl font-semibold tracking-tight">Zero-Config PHP UI Primitives</h2>
                <p class="text-xs sm:text-sm text-slate-400 leading-relaxed">
                    Import <code class="text-brand-300 font-mono">helpers/components.php</code> anywhere in your PHP views to immediately render accessible buttons, cards, toggles, badges, and modals without complex build steps or template engines.
                </p>
                <div class="flex flex-wrap gap-3 pt-2">
                    <?= ui_button('Explore Component Docs', [
                        'variant' => 'primary',
                        'size'    => 'md',
                        'href'    => base_url('design-system.php'),
                        'icon'    => 'book-open',
                    ]) ?>
                    <?= ui_button('View on GitHub', [
                        'variant' => 'glass',
                        'size'    => 'md',
                        'href'    => 'https://github.com/dev-celestia/simple-native-php',
                        'target'  => '_blank',
                        'icon'    => 'github-logo',
                        'class'   => 'bg-slate-900 text-white border-slate-800 hover:bg-slate-800',
                    ]) ?>
                </div>
            </div>

            <!-- Code Snippet Container -->
            <div class="bg-slate-900 border border-slate-800 rounded-btn p-5 font-mono text-xs text-slate-300 space-y-2 overflow-x-auto">
                <p class="text-slate-500">// 1. Import master components loader</p>
                <p class="text-amber-300">require_once <span class="text-emerald-300">'helpers/components.php'</span>;</p>
                <br>
                <p class="text-slate-500">// 2. Render buttons, badges, and cards directly</p>
                <p><span class="text-slate-500">&lt;?=</span> <span class="text-brand-300">ui_button</span>(<span class="text-emerald-300">'Save Changes'</span>, [<span class="text-amber-300">'variant'</span> =&gt; <span class="text-emerald-300">'primary'</span>, <span class="text-amber-300">'icon'</span> =&gt; <span class="text-emerald-300">'check'</span>]) <span class="text-slate-500">?&gt;</span></p>
                <p><span class="text-slate-500">&lt;?=</span> <span class="text-brand-300">ui_badge</span>(<span class="text-emerald-300">'Active'</span>, <span class="text-emerald-300">'success'</span>, [<span class="text-amber-300">'dot'</span> =&gt; <span class="text-cyan-300">true</span>]) <span class="text-slate-500">?&gt;</span></p>
                <p><span class="text-slate-500">&lt;?=</span> <span class="text-brand-300">ui_stat_card</span>(<span class="text-emerald-300">'Users'</span>, <span class="text-emerald-300">'14,250'</span>, [<span class="text-amber-300">'trend'</span> =&gt; <span class="text-emerald-300">'+12%'</span>]) <span class="text-slate-500">?&gt;</span></p>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
