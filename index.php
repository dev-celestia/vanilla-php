<?php
/**
 * Modern Lightweight Vanilla PHP UI Micro-Framework & Starter Stack
 * 
 * Master Landing Page & Architecture Overview
 */
$active_nav = 'home';
$page_title = 'VanillaPHP UI - Apple-inspired Component Library & Micro-Framework';
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

<!-- Hero Section (Apple Tactile, Translucent Glass & Fluid Motion) -->
<section class="relative bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 text-white overflow-hidden py-20 lg:py-28 border-b border-slate-800">
    <div class="absolute -top-32 -right-32 w-[550px] h-[550px] bg-brand-500/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-32 -left-32 w-[550px] h-[550px] bg-brand-400/10 rounded-full blur-3xl pointer-events-none"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="max-w-3xl mx-auto space-y-6 text-center">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-badge bg-brand-500/10 border border-brand-500/30 text-brand-300 text-xs font-semibold tracking-tight">
                <span class="w-2 h-2 rounded-full bg-brand-400 animate-pulse"></span>
                <span>⚡ Pure Vanilla PHP Micro-Framework & UI Starter Stack</span>
            </div>
            
            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-semibold tracking-tight leading-[1.12]">
                Fluid, Modern Web Apps with <br/>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-300 to-brand-500">Pure Vanilla PHP</span>
            </h1>
            
            <p class="text-slate-300 text-sm sm:text-base lg:text-lg max-w-2xl mx-auto leading-relaxed">
                A lightweight, modular UI component library and application starter built on <strong>Apple Human Interface</strong> principles, <strong>Tailwind CSS v4</strong>, <strong>Alpine.js</strong>, and <strong>MySQL PDO</strong>. Sub-50ms execution, zero vendor runtime bloat, and 100% ready for any hosting.
            </p>

            <div class="flex flex-wrap items-center justify-center gap-3.5 pt-3">
                <?= ui_button('Explore Design System', [
                    'variant' => 'primary',
                    'size'    => 'lg',
                    'href'    => base_url('design-system.php'),
                    'icon'    => 'palette',
                ]) ?>
                
                <?= ui_button('Open Live Demo Store', [
                    'variant' => 'glass',
                    'size'    => 'lg',
                    'href'    => base_url('demo.php'),
                    'icon'    => 'shopping-bag',
                    'class'   => 'bg-slate-800/80 hover:bg-slate-700 text-white border-slate-700',
                ]) ?>

                <?= ui_button('Project Scaffolder', [
                    'variant' => 'ghost',
                    'size'    => 'lg',
                    'href'    => base_url('scaffold.php'),
                    'icon'    => 'lightning',
                    'class'   => 'text-emerald-400 hover:text-emerald-300 hover:bg-emerald-950/40 border border-emerald-800/60',
                ]) ?>
            </div>

            <!-- Key Architecture Metrics -->
            <div class="pt-8 border-t border-slate-800/80 grid grid-cols-2 sm:grid-cols-4 gap-4 text-center text-xs text-slate-300">
                <div>
                    <p class="font-semibold text-white text-lg sm:text-xl tracking-tight">&lt; 50ms</p>
                    <p class="text-[11px] text-slate-400">Response Time (TTFB)</p>
                </div>
                <div>
                    <p class="font-semibold text-white text-lg sm:text-xl tracking-tight">0 MB</p>
                    <p class="text-[11px] text-slate-400">Runtime Vendor Bloat</p>
                </div>
                <div>
                    <p class="font-semibold text-white text-lg sm:text-xl tracking-tight">100%</p>
                    <p class="text-[11px] text-slate-400">cPanel / VPS Ready</p>
                </div>
                <div>
                    <p class="font-semibold text-white text-lg sm:text-xl tracking-tight">9 Palettes</p>
                    <p class="text-[11px] text-slate-400">Dynamic Theme Engine</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section: Live Token Explorer Strip -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="p-6 rounded-card bg-white border border-slate-200/80 flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="space-y-1 text-center md:text-left">
            <span class="text-[10px] font-semibold uppercase tracking-wider text-brand-600 block">Dynamic Design Tokens</span>
            <h2 class="text-lg font-semibold text-slate-900 tracking-tight">Test 9 Color Palettes & 6 Corner Radius Scales</h2>
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

<!-- Section: Architecture & Pillars -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    <div class="text-center max-w-2xl mx-auto">
        <span class="text-xs font-semibold uppercase tracking-wider text-brand-600 block">Design Foundations</span>
        <h2 class="text-2xl sm:text-3xl font-semibold text-slate-900 tracking-tight mt-1">Built for High Velocity & Simplicity</h2>
        <p class="text-xs sm:text-sm text-slate-500 mt-2 leading-relaxed">
            Combining modern frontend ergonomics with the sheer speed and deployment simplicity of PHP 8.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Pillar 1: Latency Kill -->
        <div class="p-6 rounded-card bg-white border border-slate-200/80 hover:border-brand-300 transition space-y-3">
            <div class="w-10 h-10 rounded-btn bg-brand-50 text-brand-600 flex items-center justify-center border border-brand-200/60">
                <i class="ph-bold ph-lightning text-lg"></i>
            </div>
            <h3 class="text-sm font-semibold text-slate-900 tracking-tight">Ultra Fast (TTFB &lt; 50ms)</h3>
            <p class="text-xs text-slate-500 leading-relaxed">
                Zero runtime framework overhead. Pure PHP 8 executed instantly with minimal RAM usage.
            </p>
        </div>

        <!-- Pillar 2: Materials & Depth -->
        <div class="p-6 rounded-card bg-white border border-slate-200/80 hover:border-brand-300 transition space-y-3">
            <div class="w-10 h-10 rounded-btn bg-brand-50 text-brand-600 flex items-center justify-center border border-brand-200/60">
                <i class="ph-bold ph-palette text-lg"></i>
            </div>
            <h3 class="text-sm font-semibold text-slate-900 tracking-tight">Dynamic Theme Engine</h3>
            <p class="text-xs text-slate-500 leading-relaxed">
                Runtime CSS variable injection for 8 color presets and 6 radius scales without CSS rebuilds.
            </p>
        </div>

        <!-- Pillar 3: UI Primitives -->
        <div class="p-6 rounded-card bg-white border border-slate-200/80 hover:border-brand-300 transition space-y-3">
            <div class="w-10 h-10 rounded-btn bg-brand-50 text-brand-600 flex items-center justify-center border border-brand-200/60">
                <i class="ph-bold ph-squares-four text-lg"></i>
            </div>
            <h3 class="text-sm font-semibold text-slate-900 tracking-tight">Modular UI Components</h3>
            <p class="text-xs text-slate-500 leading-relaxed">
                Render accessible, standardized buttons, cards, modals, inputs, and badges with simple PHP functions.
            </p>
        </div>

        <!-- Pillar 4: Scaffolder -->
        <div class="p-6 rounded-card bg-white border border-slate-200/80 hover:border-brand-300 transition space-y-3">
            <div class="w-10 h-10 rounded-btn bg-brand-50 text-brand-600 flex items-center justify-center border border-brand-200/60">
                <i class="ph-bold ph-rocket text-lg"></i>
            </div>
            <h3 class="text-sm font-semibold text-slate-900 tracking-tight">Instant App Scaffolder</h3>
            <p class="text-xs text-slate-500 leading-relaxed">
                Clone a standalone, clean project directly to another directory via CLI or the Web GUI installer.
            </p>
        </div>

    </div>
</section>

<!-- Section: Showcase Demo Spotlight -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 border-b border-slate-200/80 pb-6">
        <div>
            <span class="text-xs font-semibold uppercase tracking-wider text-brand-600 block">Showcase Application</span>
            <h2 class="text-2xl font-semibold text-slate-900 tracking-tight mt-1">E-Commerce & WhatsApp Demo Store</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                A complete production-grade store sample built entirely with this framework.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="<?= base_url('demo.php') ?>" class="px-4 py-2 rounded-btn bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold transition apple-tap flex items-center gap-1.5">
                <i class="ph ph-shopping-bag text-sm"></i>
                <span>Open Live Demo Store</span>
                <i class="ph ph-caret-right text-xs"></i>
            </a>
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
        <?= ui_empty_state('Demo Store Ready', 'Open the live demo store to explore the product catalog, cart drawer, and WhatsApp checkout.', [
            'buttonText' => 'Open Live Demo Store',
            'buttonHref' => base_url('demo.php'),
        ]) ?>
    <?php endif; ?>
</section>

<!-- Section: Quick Code Implementation -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mb-12">
    <div class="rounded-card bg-slate-950 text-white border border-slate-800 p-8 sm:p-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
            <div class="space-y-4">
                <span class="text-xs font-semibold uppercase tracking-wider text-brand-400 block">Developer Experience</span>
                <h2 class="text-2xl sm:text-3xl font-semibold tracking-tight">Zero-Config PHP UI Primitives</h2>
                <p class="text-xs sm:text-sm text-slate-400 leading-relaxed">
                    Import <code class="text-brand-300 font-mono">config/app.php</code> anywhere in your views to immediately render accessible buttons, cards, toggles, badges, and modals without complex build steps or template engines.
                </p>
                <div class="flex flex-wrap gap-3 pt-2">
                    <?= ui_button('Explore Component Docs', [
                        'variant' => 'primary',
                        'size'    => 'md',
                        'href'    => base_url('components.php'),
                        'icon'    => 'book-open',
                    ]) ?>
                    <?= ui_button('Open Scaffolder', [
                        'variant' => 'glass',
                        'size'    => 'md',
                        'href'    => base_url('scaffold.php'),
                        'icon'    => 'lightning',
                        'class'   => 'bg-slate-900 text-emerald-400 border-slate-800 hover:bg-slate-800',
                    ]) ?>
                </div>
            </div>

            <!-- Code Snippet Container -->
            <div class="bg-slate-900 border border-slate-800 rounded-btn p-5 font-mono text-xs text-slate-300 space-y-2 overflow-x-auto">
                <p class="text-slate-500">// 1. Load framework bootstrap</p>
                <p class="text-amber-300">require_once <span class="text-emerald-300">__DIR__ . '/config/app.php'</span>;</p>
                <br>
                <p class="text-slate-500">// 2. Render UI components anywhere</p>
                <p><span class="text-slate-500">&lt;?=</span> <span class="text-brand-300">ui_button</span>(<span class="text-emerald-300">'Save Changes'</span>, [<span class="text-amber-300">'variant'</span> =&gt; <span class="text-emerald-300">'primary'</span>, <span class="text-amber-300">'icon'</span> =&gt; <span class="text-emerald-300">'check'</span>]) <span class="text-slate-500">?&gt;</span></p>
                <p><span class="text-slate-500">&lt;?=</span> <span class="text-brand-300">ui_badge</span>(<span class="text-emerald-300">'Active'</span>, <span class="text-emerald-300">'success'</span>, [<span class="text-amber-300">'dot'</span> =&gt; <span class="text-cyan-300">true</span>]) <span class="text-slate-500">?&gt;</span></p>
                <p><span class="text-slate-500">&lt;?=</span> <span class="text-brand-300">ui_stat_card</span>(<span class="text-emerald-300">'Orders'</span>, <span class="text-emerald-300">'1,420'</span>, [<span class="text-amber-300">'trend'</span> =&gt; <span class="text-emerald-300">'+18%'</span>]) <span class="text-slate-500">?&gt;</span></p>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
