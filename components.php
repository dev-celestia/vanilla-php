<?php
/**
 * UI Component Library & Primitives Explorer
 *
 * Dedicated interactive catalog, API documentation, and live preview for all
 * Vanilla PHP UI component primitives built with Apple Human Interface standards.
 */
$active_nav = 'components';
$page_title = 'UI Component Library & Primitives - Vanilla PHP';
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/helpers/format.php';

$activeTheme = get_active_theme();

// Sample product for product-card showcase
$sampleProduct = [
    'id'            => 101,
    'name'          => 'Apple AirPods Max (Space Gray)',
    'category_name' => 'Audio & Accessories',
    'price'         => 8499000,
    'promo_price'   => 7499000,
    'stock'         => 8,
    'is_featured'   => 1,
    'image'         => 'airpods-max.jpg',
];

require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Header Banner (Apple Glass Material, Crisp Typography) -->
<section class="relative bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 text-white overflow-hidden py-14 lg:py-20 border-b border-slate-800">
    <div class="absolute -top-40 -right-40 w-[600px] h-[600px] bg-brand-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-40 -left-40 w-[600px] h-[600px] bg-brand-400/5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="max-w-3xl">
            <div class="flex flex-wrap items-center gap-2 mb-4">
                <?= ui_badge('16 UI Primitives', 'brand', ['dot' => true, 'pulse' => true]) ?>
                <?= ui_badge('Zero Dependencies', 'success') ?>
                <?= ui_badge('Pure Vanilla PHP', 'neutral') ?>
            </div>
            
            <h1 class="text-3xl sm:text-5xl font-semibold tracking-tight leading-tight">
                UI Component <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-300 to-brand-500">Catalog & Docs</span>
            </h1>
            
            <p class="text-slate-300 text-sm sm:text-base mt-4 leading-relaxed">
                Lightweight, modular UI primitives designed for zero bloat, high tactile responsiveness, and full Apple Human Interface fidelity. Copy ready-to-use PHP snippets and explore live interactive previews.
            </p>

            <div class="flex flex-wrap gap-2.5 pt-6">
                <a href="<?= base_url('design-system.php') ?>" class="px-3.5 py-1.5 rounded-btn bg-slate-800/80 hover:bg-slate-700 text-white text-xs font-semibold border border-slate-700 transition apple-tap flex items-center gap-1.5">
                    <?= ui_icon('palette', 'text-brand-400 text-sm') ?>
                    <span>Design Tokens & Themes</span>
                </a>
                <a href="<?= base_url('demo.php') ?>" class="px-3.5 py-1.5 rounded-btn bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold border border-brand-500/30 transition apple-tap flex items-center gap-1.5">
                    <?= ui_icon('shopping-bag', 'text-sm') ?>
                    <span>Open Live Store Demo</span>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Breadcrumb Strip -->
<?= ui_breadcrumb([
    ['label' => 'Design System', 'href' => base_url('design-system.php'), 'icon' => 'palette'],
    ['label' => 'UI Components', 'href' => null, 'icon' => 'squares-four'],
]) ?>

<!-- Main Documentation & Sandbox with Interactive Sidebar -->
<main 
    class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" 
    x-data="{ 
        searchQuery: '', 
        activeSection: 'comp-buttons',
        copiedSnippet: null,
        showSampleModal: false,
        activeTabDemo: 'daily',
        activeFilterSegment: 'all',
        copyCode(snippet, id) {
            navigator.clipboard.writeText(snippet);
            this.copiedSnippet = id;
            setTimeout(() => { if (this.copiedSnippet === id) this.copiedSnippet = null; }, 2000);
        },
        matchesSearch(title, keywords) {
            if (!this.searchQuery.trim()) return true;
            const q = this.searchQuery.toLowerCase().trim();
            return title.toLowerCase().includes(q) || keywords.toLowerCase().includes(q);
        }
    }">

    <div class="lg:grid lg:grid-cols-12 lg:gap-8 items-start">
        
        <!-- SIDEBAR NAVIGATION (Sticky Desktop / Quick Filter) -->
        <aside class="hidden lg:block lg:col-span-3 sticky top-20 max-h-[calc(100vh-6rem)] overflow-y-auto pr-2 space-y-6 scrollbar-thin">
            
            <!-- Live Search Filter -->
            <div class="relative">
                <input 
                    type="text" 
                    x-model="searchQuery" 
                    placeholder="Search components..." 
                    class="w-full pl-9 pr-8 py-2 text-xs rounded-input bg-white text-slate-800 border border-slate-200 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-all shadow-none">
                <i class="ph ph-magnifying-glass text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 text-sm pointer-events-none"></i>
                <button 
                    type="button" 
                    x-show="searchQuery" 
                    @click="searchQuery = ''" 
                    class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-0.5">
                    <i class="ph ph-x text-xs"></i>
                </button>
            </div>

            <!-- Category: Actions & Triggers -->
            <div class="space-y-1" x-show="matchesSearch('Buttons Action Groups Segmented Control Icon Button', 'button group click submit action')">
                <h3 class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 px-3 mb-1.5 flex items-center gap-1.5">
                    <i class="ph ph-cursor-click text-brand-600 text-xs"></i>
                    <span>Actions & Triggers</span>
                </h3>
                <nav class="space-y-0.5 text-xs">
                    <a href="#comp-buttons" 
                       x-show="matchesSearch('Buttons & Button Groups', 'button btn loading outline danger primary')"
                       :class="activeSection === 'comp-buttons' ? 'bg-brand-50 text-brand-700 font-semibold border-brand-200/80' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900 border-transparent'" 
                       class="flex items-center justify-between px-3 py-1.5 rounded-btn border transition-colors apple-tap">
                        <span class="flex items-center gap-2">
                            <i class="ph ph-hand-pointing text-sm text-slate-400"></i>
                            <span>Buttons & Groups</span>
                        </span>
                        <code class="text-[10px] font-mono text-slate-400">ui_button()</code>
                    </a>

                    <a href="#comp-tabs" 
                       x-show="matchesSearch('Tabs & Segmented Control', 'segmented control tabs switch model')"
                       :class="activeSection === 'comp-tabs' ? 'bg-brand-50 text-brand-700 font-semibold border-brand-200/80' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900 border-transparent'" 
                       class="flex items-center justify-between px-3 py-1.5 rounded-btn border transition-colors apple-tap">
                        <span class="flex items-center gap-2">
                            <i class="ph ph-tabs text-sm text-slate-400"></i>
                            <span>Segmented & Tabs</span>
                        </span>
                        <code class="text-[10px] font-mono text-slate-400">ui_tabs()</code>
                    </a>
                </nav>
            </div>

            <!-- Category: Data & Indicators -->
            <div class="space-y-1" x-show="matchesSearch('Badges Status Indicators Stat Cards Metrics Avatars Presence', 'badge status metric count user avatar indicator')">
                <h3 class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 px-3 mb-1.5 flex items-center gap-1.5">
                    <i class="ph ph-chart-bar text-brand-600 text-xs"></i>
                    <span>Data & Indicators</span>
                </h3>
                <nav class="space-y-0.5 text-xs">
                    <a href="#comp-badges" 
                       x-show="matchesSearch('Badges & Status Indicators', 'badge pill status active pulse dot')"
                       :class="activeSection === 'comp-badges' ? 'bg-brand-50 text-brand-700 font-semibold border-brand-200/80' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900 border-transparent'" 
                       class="flex items-center justify-between px-3 py-1.5 rounded-btn border transition-colors apple-tap">
                        <span class="flex items-center gap-2">
                            <i class="ph ph-tag text-sm text-slate-400"></i>
                            <span>Badges & Chips</span>
                        </span>
                        <code class="text-[10px] font-mono text-slate-400">ui_badge()</code>
                    </a>

                    <a href="#comp-stat-cards" 
                       x-show="matchesSearch('Stat Cards & Metrics', 'stat card metric trend health kpi value')"
                       :class="activeSection === 'comp-stat-cards' ? 'bg-brand-50 text-brand-700 font-semibold border-brand-200/80' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900 border-transparent'" 
                       class="flex items-center justify-between px-3 py-1.5 rounded-btn border transition-colors apple-tap">
                        <span class="flex items-center gap-2">
                            <i class="ph ph-trend-up text-sm text-slate-400"></i>
                            <span>Stat Cards</span>
                        </span>
                        <code class="text-[10px] font-mono text-slate-400">ui_stat_card()</code>
                    </a>

                    <a href="#comp-avatars" 
                       x-show="matchesSearch('Avatars & Presence Stacks', 'avatar user presence online busy stack icon box')"
                       :class="activeSection === 'comp-avatars' ? 'bg-brand-50 text-brand-700 font-semibold border-brand-200/80' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900 border-transparent'" 
                       class="flex items-center justify-between px-3 py-1.5 rounded-btn border transition-colors apple-tap">
                        <span class="flex items-center gap-2">
                            <i class="ph ph-user-circle text-sm text-slate-400"></i>
                            <span>Avatars & Stacks</span>
                        </span>
                        <code class="text-[10px] font-mono text-slate-400">ui_avatar()</code>
                    </a>
                </nav>
            </div>

            <!-- Category: Forms & Controls -->
            <div class="space-y-1" x-show="matchesSearch('Form Inputs Textarea Select Dropdown Toggles Switches', 'input select textarea switch toggle form checkbox')">
                <h3 class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 px-3 mb-1.5 flex items-center gap-1.5">
                    <i class="ph ph-textbox text-brand-600 text-xs"></i>
                    <span>Forms & Controls</span>
                </h3>
                <nav class="space-y-0.5 text-xs">
                    <a href="#comp-inputs" 
                       x-show="matchesSearch('Text Inputs & Search', 'input text search password email icon helper')"
                       :class="activeSection === 'comp-inputs' ? 'bg-brand-50 text-brand-700 font-semibold border-brand-200/80' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900 border-transparent'" 
                       class="flex items-center justify-between px-3 py-1.5 rounded-btn border transition-colors apple-tap">
                        <span class="flex items-center gap-2">
                            <i class="ph ph-text-t text-sm text-slate-400"></i>
                            <span>Text Inputs</span>
                        </span>
                        <code class="text-[10px] font-mono text-slate-400">ui_input()</code>
                    </a>

                    <a href="#comp-selects" 
                       x-show="matchesSearch('Select Dropdown & Groups', 'select dropdown optgroup options choice')"
                       :class="activeSection === 'comp-selects' ? 'bg-brand-50 text-brand-700 font-semibold border-brand-200/80' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900 border-transparent'" 
                       class="flex items-center justify-between px-3 py-1.5 rounded-btn border transition-colors apple-tap">
                        <span class="flex items-center gap-2">
                            <i class="ph ph-list-dashes text-sm text-slate-400"></i>
                            <span>Select Dropdowns</span>
                        </span>
                        <code class="text-[10px] font-mono text-slate-400">ui_select()</code>
                    </a>

                    <a href="#comp-textareas" 
                       x-show="matchesSearch('Textareas & Multiline', 'textarea multiline comment description')"
                       :class="activeSection === 'comp-textareas' ? 'bg-brand-50 text-brand-700 font-semibold border-brand-200/80' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900 border-transparent'" 
                       class="flex items-center justify-between px-3 py-1.5 rounded-btn border transition-colors apple-tap">
                        <span class="flex items-center gap-2">
                            <i class="ph ph-article text-sm text-slate-400"></i>
                            <span>Textareas</span>
                        </span>
                        <code class="text-[10px] font-mono text-slate-400">ui_textarea()</code>
                    </a>

                    <a href="#comp-toggles" 
                       x-show="matchesSearch('iOS Toggles & Switches', 'toggle switch switch checkbox boolean tactile')"
                       :class="activeSection === 'comp-toggles' ? 'bg-brand-50 text-brand-700 font-semibold border-brand-200/80' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900 border-transparent'" 
                       class="flex items-center justify-between px-3 py-1.5 rounded-btn border transition-colors apple-tap">
                        <span class="flex items-center gap-2">
                            <i class="ph ph-toggle-left text-sm text-slate-400"></i>
                            <span>iOS Toggles</span>
                        </span>
                        <code class="text-[10px] font-mono text-slate-400">ui_toggle()</code>
                    </a>
                </nav>
            </div>

            <!-- Category: Surfaces & Layout -->
            <div class="space-y-1" x-show="matchesSearch('Cards Surfaces Containers Breadcrumbs Empty States', 'card glass dark surface breadcrumb empty state')">
                <h3 class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 px-3 mb-1.5 flex items-center gap-1.5">
                    <i class="ph ph-square-half text-brand-600 text-xs"></i>
                    <span>Surfaces & Layout</span>
                </h3>
                <nav class="space-y-0.5 text-xs">
                    <a href="#comp-cards" 
                       x-show="matchesSearch('Cards & Surface Containers', 'card glass dark surface interactive container')"
                       :class="activeSection === 'comp-cards' ? 'bg-brand-50 text-brand-700 font-semibold border-brand-200/80' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900 border-transparent'" 
                       class="flex items-center justify-between px-3 py-1.5 rounded-btn border transition-colors apple-tap">
                        <span class="flex items-center gap-2">
                            <i class="ph ph-cube text-sm text-slate-400"></i>
                            <span>Cards & Surfaces</span>
                        </span>
                        <code class="text-[10px] font-mono text-slate-400">ui_card()</code>
                    </a>

                    <a href="#comp-breadcrumbs" 
                       x-show="matchesSearch('Breadcrumbs & Trails', 'breadcrumb navigation trail path caret')"
                       :class="activeSection === 'comp-breadcrumbs' ? 'bg-brand-50 text-brand-700 font-semibold border-brand-200/80' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900 border-transparent'" 
                       class="flex items-center justify-between px-3 py-1.5 rounded-btn border transition-colors apple-tap">
                        <span class="flex items-center gap-2">
                            <i class="ph ph-caret-right text-sm text-slate-400"></i>
                            <span>Breadcrumbs</span>
                        </span>
                        <code class="text-[10px] font-mono text-slate-400">ui_breadcrumb()</code>
                    </a>

                    <a href="#comp-empty-states" 
                       x-show="matchesSearch('Empty States & Placeholders', 'empty state placeholder zero data no results')"
                       :class="activeSection === 'comp-empty-states' ? 'bg-brand-50 text-brand-700 font-semibold border-brand-200/80' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900 border-transparent'" 
                       class="flex items-center justify-between px-3 py-1.5 rounded-btn border transition-colors apple-tap">
                        <span class="flex items-center gap-2">
                            <i class="ph ph-package text-sm text-slate-400"></i>
                            <span>Empty States</span>
                        </span>
                        <code class="text-[10px] font-mono text-slate-400">ui_empty_state()</code>
                    </a>
                </nav>
            </div>

            <!-- Category: Overlays & Feedback -->
            <div class="space-y-1" x-show="matchesSearch('Alerts Notifications Banners Modal Sheet Dialogs', 'alert banner dismiss notification modal dialog sheet')">
                <h3 class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 px-3 mb-1.5 flex items-center gap-1.5">
                    <i class="ph ph-bell text-brand-600 text-xs"></i>
                    <span>Overlays & Feedback</span>
                </h3>
                <nav class="space-y-0.5 text-xs">
                    <a href="#comp-alerts" 
                       x-show="matchesSearch('Alerts & Notifications', 'alert banner dismiss success warning danger info')"
                       :class="activeSection === 'comp-alerts' ? 'bg-brand-50 text-brand-700 font-semibold border-brand-200/80' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900 border-transparent'" 
                       class="flex items-center justify-between px-3 py-1.5 rounded-btn border transition-colors apple-tap">
                        <span class="flex items-center gap-2">
                            <i class="ph ph-warning-circle text-sm text-slate-400"></i>
                            <span>Alert Banners</span>
                        </span>
                        <code class="text-[10px] font-mono text-slate-400">ui_alert()</code>
                    </a>

                    <a href="#comp-modals" 
                       x-show="matchesSearch('Modal Sheets & Dialogs', 'modal dialog sheet backdrop blur overlay')"
                       :class="activeSection === 'comp-modals' ? 'bg-brand-50 text-brand-700 font-semibold border-brand-200/80' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900 border-transparent'" 
                       class="flex items-center justify-between px-3 py-1.5 rounded-btn border transition-colors apple-tap">
                        <span class="flex items-center gap-2">
                            <i class="ph ph-browsers text-sm text-slate-400"></i>
                            <span>Modal Sheets</span>
                        </span>
                        <code class="text-[10px] font-mono text-slate-400">ui_modal()</code>
                    </a>
                </nav>
            </div>

            <!-- Category: Specialized & Commerce -->
            <div class="space-y-1" x-show="matchesSearch('Product Cards E-Commerce Phosphor Icons', 'product card shop store icon phosphor')">
                <h3 class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 px-3 mb-1.5 flex items-center gap-1.5">
                    <i class="ph ph-sparkle text-brand-600 text-xs"></i>
                    <span>Specialized & Icons</span>
                </h3>
                <nav class="space-y-0.5 text-xs">
                    <a href="#comp-product-cards" 
                       x-show="matchesSearch('Product Cards & Commerce', 'product commerce price discount cart store')"
                       :class="activeSection === 'comp-product-cards' ? 'bg-brand-50 text-brand-700 font-semibold border-brand-200/80' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900 border-transparent'" 
                       class="flex items-center justify-between px-3 py-1.5 rounded-btn border transition-colors apple-tap">
                        <span class="flex items-center gap-2">
                            <i class="ph ph-shopping-bag text-sm text-slate-400"></i>
                            <span>Product Card</span>
                        </span>
                        <code class="text-[10px] font-mono text-slate-400">ui_product_card()</code>
                    </a>

                    <a href="#comp-icons" 
                       x-show="matchesSearch('Phosphor Icons Helper', 'icon phosphor font svg box')"
                       :class="activeSection === 'comp-icons' ? 'bg-brand-50 text-brand-700 font-semibold border-brand-200/80' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900 border-transparent'" 
                       class="flex items-center justify-between px-3 py-1.5 rounded-btn border transition-colors apple-tap">
                        <span class="flex items-center gap-2">
                            <i class="ph ph-diamonds-four text-sm text-slate-400"></i>
                            <span>Phosphor Icons</span>
                        </span>
                        <code class="text-[10px] font-mono text-slate-400">ui_icon()</code>
                    </a>
                </nav>
            </div>

            <!-- Quick Token Card in Sidebar -->
            <div class="p-4 rounded-card bg-slate-50 border border-slate-200/80 text-xs space-y-2">
                <div class="flex items-center justify-between">
                    <span class="font-semibold text-slate-800">Theme Engine</span>
                    <span class="w-2 h-2 rounded-full bg-brand-500 animate-pulse"></span>
                </div>
                <p class="text-[11px] text-slate-500 leading-normal">
                    Palette: <strong class="text-slate-700"><?= $activeTheme['palette']['name'] ?></strong><br>
                    Radius: <strong class="text-slate-700"><?= $activeTheme['radius']['name'] ?></strong>
                </p>
                <a href="<?= base_url('design-system.php') ?>" class="inline-flex items-center gap-1 text-[11px] font-semibold text-brand-600 hover:text-brand-700 transition">
                    <span>Adjust Theme Tokens</span>
                    <i class="ph ph-arrow-right"></i>
                </a>
            </div>

        </aside>

        <!-- MAIN COMPONENT DOCUMENTATION & SHOWCASE -->
        <div class="lg:col-span-9 space-y-12">

            <!-- Mobile Quick Category Bar -->
            <div class="lg:hidden p-3 rounded-card bg-white border border-slate-200/80 sticky top-16 z-20 overflow-x-auto scrollbar-none flex items-center gap-2">
                <a href="#comp-buttons" class="px-3 py-1.5 rounded-btn bg-slate-100 hover:bg-brand-50 text-slate-700 text-xs font-semibold whitespace-nowrap">Buttons</a>
                <a href="#comp-badges" class="px-3 py-1.5 rounded-btn bg-slate-100 hover:bg-brand-50 text-slate-700 text-xs font-semibold whitespace-nowrap">Badges</a>
                <a href="#comp-inputs" class="px-3 py-1.5 rounded-btn bg-slate-100 hover:bg-brand-50 text-slate-700 text-xs font-semibold whitespace-nowrap">Inputs</a>
                <a href="#comp-toggles" class="px-3 py-1.5 rounded-btn bg-slate-100 hover:bg-brand-50 text-slate-700 text-xs font-semibold whitespace-nowrap">Toggles</a>
                <a href="#comp-cards" class="px-3 py-1.5 rounded-btn bg-slate-100 hover:bg-brand-50 text-slate-700 text-xs font-semibold whitespace-nowrap">Cards</a>
                <a href="#comp-alerts" class="px-3 py-1.5 rounded-btn bg-slate-100 hover:bg-brand-50 text-slate-700 text-xs font-semibold whitespace-nowrap">Alerts</a>
                <a href="#comp-stat-cards" class="px-3 py-1.5 rounded-btn bg-slate-100 hover:bg-brand-50 text-slate-700 text-xs font-semibold whitespace-nowrap">Stats</a>
                <a href="#comp-avatars" class="px-3 py-1.5 rounded-btn bg-slate-100 hover:bg-brand-50 text-slate-700 text-xs font-semibold whitespace-nowrap">Avatars</a>
                <a href="#comp-tabs" class="px-3 py-1.5 rounded-btn bg-slate-100 hover:bg-brand-50 text-slate-700 text-xs font-semibold whitespace-nowrap">Tabs</a>
                <a href="#comp-modals" class="px-3 py-1.5 rounded-btn bg-slate-100 hover:bg-brand-50 text-slate-700 text-xs font-semibold whitespace-nowrap">Modals</a>
            </div>

            <!-- ========================================== -->
            <!-- 1. BUTTONS & BUTTON GROUPS                 -->
            <!-- ========================================== -->
            <section id="comp-buttons" class="rounded-card bg-white border border-slate-200/80 p-6 sm:p-8 space-y-6 scroll-mt-24" x-show="matchesSearch('Buttons & Button Groups', 'button btn loading outline danger primary subtle ghost')">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-2">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-brand-500"></span>
                            <h2 class="text-xl font-semibold text-slate-900 tracking-tight">Buttons & Action Groups</h2>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">High-tactile pointer-down response, smooth loading state, slot icons, and segmented groups.</p>
                    </div>
                    <button 
                        type="button" 
                        @click="copyCode('<?= addslashes("<?= ui_button('Primary Action', ['variant' => 'primary', 'icon' => 'sparkle']) ?>") ?>', 'code-btn')" 
                        class="px-2.5 py-1 rounded-btn bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-mono transition apple-tap flex items-center gap-1.5 self-start">
                        <i class="ph" :class="copiedSnippet === 'code-btn' ? 'ph-check text-emerald-600' : 'ph-copy'"></i>
                        <span x-text="copiedSnippet === 'code-btn' ? 'Copied!' : 'Copy Code'">Copy Code</span>
                    </button>
                </div>

                <!-- Variants Demo -->
                <div class="space-y-3">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-400 block">Variants</span>
                    <div class="flex flex-wrap items-center gap-3">
                        <?= ui_button('Primary Button', ['variant' => 'primary', 'icon' => 'sparkle']) ?>
                        <?= ui_button('Secondary', ['variant' => 'secondary', 'icon' => 'gear']) ?>
                        <?= ui_button('Subtle Brand', ['variant' => 'subtle']) ?>
                        <?= ui_button('Outline', ['variant' => 'outline', 'icon' => 'arrow-square-out']) ?>
                        <?= ui_button('Ghost Button', ['variant' => 'ghost']) ?>
                        <?= ui_button('Danger Action', ['variant' => 'danger', 'icon' => 'trash']) ?>
                        <?= ui_button('Glass Material', ['variant' => 'glass', 'icon' => 'drop']) ?>
                        <?= ui_button('Loading State', ['variant' => 'primary', 'loading' => true]) ?>
                        <?= ui_button('Disabled', ['variant' => 'secondary', 'disabled' => true]) ?>
                    </div>
                </div>

                <!-- Sizes Demo -->
                <div class="space-y-3">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-400 block">Sizes (xs, sm, md, lg, xl)</span>
                    <div class="flex flex-wrap items-center gap-3">
                        <?= ui_button('Extra Small (xs)', ['size' => 'xs', 'icon' => 'check']) ?>
                        <?= ui_button('Small (sm)', ['size' => 'sm', 'icon' => 'check']) ?>
                        <?= ui_button('Medium (md)', ['size' => 'md', 'icon' => 'check']) ?>
                        <?= ui_button('Large (lg)', ['size' => 'lg', 'icon' => 'check']) ?>
                        <?= ui_button('Extra Large (xl)', ['size' => 'xl', 'icon' => 'check']) ?>
                    </div>
                </div>

                <!-- Button Group Demo -->
                <div class="space-y-3">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-400 block">Segmented Button Groups</span>
                    <div class="flex flex-wrap items-center gap-4">
                        <?= ui_button_group([
                            ['label' => 'Daily', 'options' => ['variant' => 'secondary', 'size' => 'sm']],
                            ['label' => 'Weekly', 'options' => ['variant' => 'primary', 'size' => 'sm']],
                            ['label' => 'Monthly', 'options' => ['variant' => 'secondary', 'size' => 'sm']],
                            ['label' => 'Yearly', 'options' => ['variant' => 'secondary', 'size' => 'sm']],
                        ]) ?>

                        <?= ui_button_group([
                            ['label' => 'Grid', 'options' => ['variant' => 'primary', 'size' => 'sm', 'icon' => 'squares-four']],
                            ['label' => 'List', 'options' => ['variant' => 'secondary', 'size' => 'sm', 'icon' => 'list']],
                        ]) ?>
                    </div>
                </div>

                <!-- Code Example Display -->
                <div class="rounded-btn bg-slate-900 text-slate-200 p-4 font-mono text-xs overflow-x-auto">
                    <span class="text-slate-500">// Standard button usage</span><br>
                    <span class="text-brand-400">&lt;?=</span> <span class="text-amber-300">ui_button</span>(<span class="text-emerald-300">'Save Changes'</span>, [<br>
                    &nbsp;&nbsp;<span class="text-sky-300">'variant'</span> =&gt; <span class="text-emerald-300">'primary'</span>, <span class="text-slate-500">// primary, secondary, subtle, outline, ghost, danger, glass</span><br>
                    &nbsp;&nbsp;<span class="text-sky-300">'size'</span>    =&gt; <span class="text-emerald-300">'md'</span>,      <span class="text-slate-500">// xs, sm, md, lg, xl</span><br>
                    &nbsp;&nbsp;<span class="text-sky-300">'icon'</span>    =&gt; <span class="text-emerald-300">'check'</span>,   <span class="text-slate-500">// Phosphor icon name</span><br>
                    &nbsp;&nbsp;<span class="text-sky-300">'loading'</span> =&gt; <span class="text-rose-400">false</span>,    <span class="text-slate-500">// renders smooth spinner</span><br>
                    ]) <span class="text-brand-400">?&gt;</span>
                </div>
            </section>

            <!-- ========================================== -->
            <!-- 2. BADGES & STATUS INDICATORS              -->
            <!-- ========================================== -->
            <section id="comp-badges" class="rounded-card bg-white border border-slate-200/80 p-6 sm:p-8 space-y-6 scroll-mt-24" x-show="matchesSearch('Badges & Status Indicators', 'badge pill status active pulse dot chip tag')">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-2">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-brand-500"></span>
                            <h2 class="text-xl font-semibold text-slate-900 tracking-tight">Badges & Live Status Indicators</h2>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">Compact pills, live pulsating dots, semantic color states, and automated status mappings.</p>
                    </div>
                    <button 
                        type="button" 
                        @click="copyCode('<?= addslashes("<?= ui_badge('Active', 'success', ['dot' => true, 'pulse' => true]) ?>") ?>', 'code-badge')" 
                        class="px-2.5 py-1 rounded-btn bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-mono transition apple-tap flex items-center gap-1.5 self-start">
                        <i class="ph" :class="copiedSnippet === 'code-badge' ? 'ph-check text-emerald-600' : 'ph-copy'"></i>
                        <span x-text="copiedSnippet === 'code-badge' ? 'Copied!' : 'Copy Code'">Copy Code</span>
                    </button>
                </div>

                <!-- Badges Grid -->
                <div class="space-y-3">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-400 block">Variants & Live Dots</span>
                    <div class="flex flex-wrap items-center gap-3">
                        <?= ui_badge('Brand Badge', 'brand', ['dot' => true]) ?>
                        <?= ui_badge('Live Status', 'brand', ['dot' => true, 'pulse' => true]) ?>
                        <?= ui_badge('Neutral Chip', 'neutral') ?>
                        <?= ui_badge('Active / Published', 'success', ['dot' => true]) ?>
                        <?= ui_badge('Pending Review', 'warning', ['dot' => true]) ?>
                        <?= ui_badge('Rejected / Error', 'danger', ['dot' => true]) ?>
                        <?= ui_badge('System Notice', 'info', ['icon' => 'info']) ?>
                        <?= ui_badge('Dark Pill', 'dark', ['rounded' => 'full']) ?>
                        <?= ui_badge('Glass Material', 'glass', ['rounded' => 'full']) ?>
                    </div>
                </div>

                <!-- Automated Status Badges -->
                <div class="space-y-3">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-400 block">Automated Status Helper (ui_status_badge)</span>
                    <div class="flex flex-wrap items-center gap-3">
                        <?= ui_status_badge('paid') ?>
                        <?= ui_status_badge('completed') ?>
                        <?= ui_status_badge('pending') ?>
                        <?= ui_status_badge('processing') ?>
                        <?= ui_status_badge('shipped') ?>
                        <?= ui_status_badge('cancelled') ?>
                        <?= ui_status_badge('draft') ?>
                    </div>
                </div>

                <!-- Code Example Display -->
                <div class="rounded-btn bg-slate-900 text-slate-200 p-4 font-mono text-xs overflow-x-auto">
                    <span class="text-slate-500">// Custom badge with pulse dot</span><br>
                    <span class="text-brand-400">&lt;?=</span> <span class="text-amber-300">ui_badge</span>(<span class="text-emerald-300">'Live Pulse'</span>, <span class="text-emerald-300">'brand'</span>, [<span class="text-sky-300">'dot'</span> =&gt; <span class="text-rose-400">true</span>, <span class="text-sky-300">'pulse'</span> =&gt; <span class="text-rose-400">true</span>]) <span class="text-brand-400">?&gt;</span><br><br>
                    <span class="text-slate-500">// Automated commerce status badge</span><br>
                    <span class="text-brand-400">&lt;?=</span> <span class="text-amber-300">ui_status_badge</span>(<span class="text-emerald-300">'completed'</span>) <span class="text-brand-400">?&gt;</span>
                </div>
            </section>

            <!-- ========================================== -->
            <!-- 3. FORM INPUTS & SEARCH                    -->
            <!-- ========================================== -->
            <section id="comp-inputs" class="rounded-card bg-white border border-slate-200/80 p-6 sm:p-8 space-y-6 scroll-mt-24" x-show="matchesSearch('Text Inputs & Search', 'input search text password email helper validation icon')">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-2">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-brand-500"></span>
                            <h2 class="text-xl font-semibold text-slate-900 tracking-tight">Form Text Inputs & Search</h2>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">Optical typography, left/right icon slots, helper texts, and inline error validation states.</p>
                    </div>
                    <button 
                        type="button" 
                        @click="copyCode('<?= addslashes("<?= ui_input('email', ['label' => 'Email Address', 'type' => 'email', 'placeholder' => 'alex@example.com', 'icon' => 'envelope']) ?>") ?>', 'code-input')" 
                        class="px-2.5 py-1 rounded-btn bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-mono transition apple-tap flex items-center gap-1.5 self-start">
                        <i class="ph" :class="copiedSnippet === 'code-input' ? 'ph-check text-emerald-600' : 'ph-copy'"></i>
                        <span x-text="copiedSnippet === 'code-input' ? 'Copied!' : 'Copy Code'">Copy Code</span>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                    <?= ui_input('demo_name', [
                        'label'       => 'Full Name',
                        'placeholder' => 'e.g. Sarah Connor',
                        'icon'        => 'user',
                        'helper'      => 'As registered on official identification',
                    ]) ?>

                    <?= ui_input('demo_search', [
                        'label'       => 'Search Query',
                        'placeholder' => 'Search products or docs...',
                        'icon'        => 'magnifying-glass',
                        'iconRight'   => 'command',
                        'helper'      => 'Supports keyboard focus shortcut',
                    ]) ?>

                    <?= ui_input('demo_email', [
                        'label'       => 'Email Address',
                        'type'        => 'email',
                        'value'       => 'alex@invalid-domain',
                        'icon'        => 'envelope',
                        'error'       => 'Please provide a valid domain email address',
                    ]) ?>

                    <?= ui_input('demo_pass', [
                        'label'       => 'Password',
                        'type'        => 'password',
                        'placeholder' => '••••••••••••',
                        'icon'        => 'lock-key',
                        'helper'      => 'Must contain at least 8 characters',
                    ]) ?>
                </div>

                <!-- Code Example Display -->
                <div class="rounded-btn bg-slate-900 text-slate-200 p-4 font-mono text-xs overflow-x-auto">
                    <span class="text-brand-400">&lt;?=</span> <span class="text-amber-300">ui_input</span>(<span class="text-emerald-300">'email'</span>, [<br>
                    &nbsp;&nbsp;<span class="text-sky-300">'label'</span>       =&gt; <span class="text-emerald-300">'Email Address'</span>,<br>
                    &nbsp;&nbsp;<span class="text-sky-300">'type'</span>        =&gt; <span class="text-emerald-300">'email'</span>,<br>
                    &nbsp;&nbsp;<span class="text-sky-300">'placeholder'</span> =&gt; <span class="text-emerald-300">'alex@example.com'</span>,<br>
                    &nbsp;&nbsp;<span class="text-sky-300">'icon'</span>        =&gt; <span class="text-emerald-300">'envelope'</span>,<br>
                    &nbsp;&nbsp;<span class="text-sky-300">'helper'</span>      =&gt; <span class="text-emerald-300">'We will never share your email.'</span>,<br>
                    ]) <span class="text-brand-400">?&gt;</span>
                </div>
            </section>

            <!-- ========================================== -->
            <!-- 4. SELECTS & DROPDOWNS                     -->
            <!-- ========================================== -->
            <section id="comp-selects" class="rounded-card bg-white border border-slate-200/80 p-6 sm:p-8 space-y-6 scroll-mt-24" x-show="matchesSearch('Select Dropdown & Groups', 'select dropdown optgroup options choice')">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-2">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-brand-500"></span>
                            <h2 class="text-xl font-semibold text-slate-900 tracking-tight">Select Dropdowns & Option Groups</h2>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">Custom styled Apple caret dropdowns, optgroup hierarchy, and validation states.</p>
                    </div>
                    <button 
                        type="button" 
                        @click="copyCode('<?= addslashes("<?= ui_select('role', ['admin' => 'Administrator', 'user' => 'Regular User'], ['label' => 'Account Role']) ?>") ?>', 'code-select')" 
                        class="px-2.5 py-1 rounded-btn bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-mono transition apple-tap flex items-center gap-1.5 self-start">
                        <i class="ph" :class="copiedSnippet === 'code-select' ? 'ph-check text-emerald-600' : 'ph-copy'"></i>
                        <span x-text="copiedSnippet === 'code-select' ? 'Copied!' : 'Copy Code'">Copy Code</span>
                    </button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 items-start">
                    <?= ui_select('category_sample', [
                        'electronics' => 'Electronics & Gadgets',
                        'apparel'     => 'Apparel & Clothing',
                        'home'        => 'Home & Living',
                        'books'       => 'Books & Publications',
                    ], [
                        'label'       => 'Standard Category Select',
                        'placeholder' => 'Choose a category...',
                        'helper'      => 'Single-level associative array',
                    ]) ?>

                    <?= ui_select('grouped_sample', [
                        'Frontend Stack' => [
                            'tailwind' => 'Tailwind CSS v4',
                            'alpine'   => 'Alpine.js v3',
                            'phosphor' => 'Phosphor Icons',
                        ],
                        'Backend Stack' => [
                            'php8'  => 'Pure Vanilla PHP 8.2+',
                            'mysql' => 'MySQL PDO Prepared',
                            'auth'  => 'Vanilla Session Auth',
                        ],
                    ], [
                        'label'       => 'Grouped Option Select (Optgroups)',
                        'selected'    => 'php8',
                        'helper'      => 'Multi-level nested array for optgroups',
                    ]) ?>
                </div>

                <!-- Code Example Display -->
                <div class="rounded-btn bg-slate-900 text-slate-200 p-4 font-mono text-xs overflow-x-auto">
                    <span class="text-brand-400">&lt;?=</span> <span class="text-amber-300">ui_select</span>(<span class="text-emerald-300">'country'</span>, [<br>
                    &nbsp;&nbsp;<span class="text-emerald-300">'id'</span> =&gt; <span class="text-emerald-300">'Indonesia'</span>,<br>
                    &nbsp;&nbsp;<span class="text-emerald-300">'us'</span> =&gt; <span class="text-emerald-300">'United States'</span>,<br>
                    &nbsp;&nbsp;<span class="text-emerald-300">'jp'</span> =&gt; <span class="text-emerald-300">'Japan'</span>,<br>
                    ], [<span class="text-sky-300">'label'</span> =&gt; <span class="text-emerald-300">'Country of Residence'</span>, <span class="text-sky-300">'selected'</span> =&gt; <span class="text-emerald-300">'id'</span>]) <span class="text-brand-400">?&gt;</span>
                </div>
            </section>

            <!-- ========================================== -->
            <!-- 5. TEXTAREAS & MULTILINE                   -->
            <!-- ========================================== -->
            <section id="comp-textareas" class="rounded-card bg-white border border-slate-200/80 p-6 sm:p-8 space-y-6 scroll-mt-24" x-show="matchesSearch('Textareas & Multiline', 'textarea multiline comment note message')">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-2">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-brand-500"></span>
                            <h2 class="text-xl font-semibold text-slate-900 tracking-tight">Textareas & Multiline Inputs</h2>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">Multiline text area with custom row height, helper description, and validation errors.</p>
                    </div>
                    <button 
                        type="button" 
                        @click="copyCode('<?= addslashes("<?= ui_textarea('message', ['label' => 'Order Notes', 'rows' => 4, 'placeholder' => 'Provide additional details...']) ?>") ?>', 'code-textarea')" 
                        class="px-2.5 py-1 rounded-btn bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-mono transition apple-tap flex items-center gap-1.5 self-start">
                        <i class="ph" :class="copiedSnippet === 'code-textarea' ? 'ph-check text-emerald-600' : 'ph-copy'"></i>
                        <span x-text="copiedSnippet === 'code-textarea' ? 'Copied!' : 'Copy Code'">Copy Code</span>
                    </button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 items-start">
                    <?= ui_textarea('notes_sample', [
                        'label'       => 'Order Delivery Instructions',
                        'rows'        => 3,
                        'placeholder' => 'e.g. Leave package by the front door...',
                        'helper'      => 'Maximum 250 characters',
                    ]) ?>

                    <?= ui_textarea('error_sample', [
                        'label'       => 'Review Feedback',
                        'rows'        => 3,
                        'value'       => 'Very short',
                        'error'       => 'Feedback must be at least 20 characters in length.',
                    ]) ?>
                </div>

                <!-- Code Example Display -->
                <div class="rounded-btn bg-slate-900 text-slate-200 p-4 font-mono text-xs overflow-x-auto">
                    <span class="text-brand-400">&lt;?=</span> <span class="text-amber-300">ui_textarea</span>(<span class="text-emerald-300">'bio'</span>, [<br>
                    &nbsp;&nbsp;<span class="text-sky-300">'label'</span>       =&gt; <span class="text-emerald-300">'Short Bio'</span>,<br>
                    &nbsp;&nbsp;<span class="text-sky-300">'rows'</span>        =&gt; <span class="text-amber-300">4</span>,<br>
                    &nbsp;&nbsp;<span class="text-sky-300">'placeholder'</span> =&gt; <span class="text-emerald-300">'Tell us about yourself...'</span>,<br>
                    ]) <span class="text-brand-400">?&gt;</span>
                </div>
            </section>

            <!-- ========================================== -->
            <!-- 6. IOS TOGGLES & SWITCHES                  -->
            <!-- ========================================== -->
            <section id="comp-toggles" class="rounded-card bg-white border border-slate-200/80 p-6 sm:p-8 space-y-6 scroll-mt-24" x-show="matchesSearch('iOS Toggles & Switches', 'toggle switch checkbox boolean setting active')">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-2">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-brand-500"></span>
                            <h2 class="text-xl font-semibold text-slate-900 tracking-tight">Tactile iOS Switches & Toggles</h2>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">Smooth spring-animated toggle controls with label and description slots.</p>
                    </div>
                    <button 
                        type="button" 
                        @click="copyCode('<?= addslashes("<?= ui_toggle('notify_email', 'Receive Email Notifications', true, ['helper' => 'Weekly product summaries']) ?>") ?>', 'code-toggle')" 
                        class="px-2.5 py-1 rounded-btn bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-mono transition apple-tap flex items-center gap-1.5 self-start">
                        <i class="ph" :class="copiedSnippet === 'code-toggle' ? 'ph-check text-emerald-600' : 'ph-copy'"></i>
                        <span x-text="copiedSnippet === 'code-toggle' ? 'Copied!' : 'Copy Code'">Copy Code</span>
                    </button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 items-start">
                    <div class="space-y-4 p-5 rounded-btn bg-slate-50 border border-slate-200/70">
                        <?= ui_toggle('sample_toggle_1', 'Enable Push Notifications', true, [
                            'helper' => 'Receive browser notifications on status changes',
                        ]) ?>

                        <?= ui_toggle('sample_toggle_2', 'Two-Factor Authentication (2FA)', false, [
                            'helper' => 'Enforce OTP authentication for all logins',
                        ]) ?>
                    </div>

                    <div class="space-y-4 p-5 rounded-btn bg-slate-50 border border-slate-200/70">
                        <?= ui_toggle('sample_toggle_sm', 'Compact Small Switch', true, [
                            'size'   => 'sm',
                            'helper' => 'Used in dense table rows or toolbars',
                        ]) ?>

                        <?= ui_toggle('sample_toggle_dis', 'Disabled Toggle Feature', true, [
                            'disabled' => true,
                            'helper'   => 'Unavailable for non-administrator accounts',
                        ]) ?>
                    </div>
                </div>

                <!-- Code Example Display -->
                <div class="rounded-btn bg-slate-900 text-slate-200 p-4 font-mono text-xs overflow-x-auto">
                    <span class="text-brand-400">&lt;?=</span> <span class="text-amber-300">ui_toggle</span>(<span class="text-emerald-300">'dark_mode'</span>, <span class="text-emerald-300">'Enable Dark Mode'</span>, <span class="text-rose-400">true</span>, [<br>
                    &nbsp;&nbsp;<span class="text-sky-300">'helper'</span> =&gt; <span class="text-emerald-300">'Automatic contrast switching based on system preferences'</span>,<br>
                    &nbsp;&nbsp;<span class="text-sky-300">'size'</span>   =&gt; <span class="text-emerald-300">'md'</span>, <span class="text-slate-500">// sm, md</span><br>
                    ]) <span class="text-brand-400">?&gt;</span>
                </div>
            </section>

            <!-- ========================================== -->
            <!-- 7. CARDS & SURFACES                        -->
            <!-- ========================================== -->
            <section id="comp-cards" class="rounded-card bg-white border border-slate-200/80 p-6 sm:p-8 space-y-6 scroll-mt-24" x-show="matchesSearch('Cards & Surface Containers', 'card glass dark surface interactive container material')">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-2">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-brand-500"></span>
                            <h2 class="text-xl font-semibold text-slate-900 tracking-tight">Surfaces & Modular Cards</h2>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">Apple hairline borders, header slots, translucent glass materials, and dark contrast cards.</p>
                    </div>
                    <button 
                        type="button" 
                        @click="copyCode('<?= addslashes("<?= ui_card('<p class=\"text-xs text-slate-600\">Card content...</p>', ['title' => 'Surface Title', 'icon' => 'cube', 'headerAction' => ui_badge('Active', 'success')]) ?>") ?>', 'code-card')" 
                        class="px-2.5 py-1 rounded-btn bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-mono transition apple-tap flex items-center gap-1.5 self-start">
                        <i class="ph" :class="copiedSnippet === 'code-card' ? 'ph-check text-emerald-600' : 'ph-copy'"></i>
                        <span x-text="copiedSnippet === 'code-card' ? 'Copied!' : 'Copy Code'">Copy Code</span>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <?= ui_card(
                        '<p class="text-xs text-slate-600 leading-relaxed">Standard card container with crisp hairline borders, header title, and action slot.</p>',
                        [
                            'title'        => 'Standard Surface',
                            'subtitle'     => 'Default crisp card container',
                            'icon'         => 'cube',
                            'headerAction' => ui_badge('Active', 'success'),
                            'footer'       => '<span class="text-[11px] text-slate-400">Updated 2 mins ago</span>',
                        ]
                    ) ?>

                    <?= ui_card(
                        '<p class="text-xs text-slate-600 leading-relaxed">Translucent Apple material card with backdrop blur and subtle ambient depth.</p>',
                        [
                            'title'        => 'Translucent Glass Card',
                            'subtitle'     => 'Backdrop blur (20px)',
                            'icon'         => 'drop',
                            'variant'      => 'glass',
                            'headerAction' => ui_button('Action', ['size' => 'xs', 'variant' => 'subtle']),
                            'footer'       => '<span class="text-[11px] text-slate-500">Translucent material</span>',
                        ]
                    ) ?>

                    <?= ui_card(
                        '<p class="text-xs text-slate-300 leading-relaxed">Dark-mode contrast card for high-priority sections, terminal CLI, or code blocks.</p>',
                        [
                            'title'        => 'Dark Pro Card',
                            'subtitle'     => 'High contrast dark surface',
                            'icon'         => 'terminal',
                            'variant'      => 'dark',
                            'headerAction' => ui_badge('CLI Ready', 'brand'),
                            'footer'       => '<span class="text-[11px] text-slate-400">Pure Vanilla PHP</span>',
                        ]
                    ) ?>
                </div>

                <!-- Code Example Display -->
                <div class="rounded-btn bg-slate-900 text-slate-200 p-4 font-mono text-xs overflow-x-auto">
                    <span class="text-brand-400">&lt;?=</span> <span class="text-amber-300">ui_card</span>(<span class="text-emerald-300">'&lt;p class="text-xs"&gt;Body content&lt;/p&gt;'</span>, [<br>
                    &nbsp;&nbsp;<span class="text-sky-300">'title'</span>        =&gt; <span class="text-emerald-300">'Card Title'</span>,<br>
                    &nbsp;&nbsp;<span class="text-sky-300">'subtitle'</span>     =&gt; <span class="text-emerald-300">'Supporting description'</span>,<br>
                    &nbsp;&nbsp;<span class="text-sky-300">'icon'</span>         =&gt; <span class="text-emerald-300">'cube'</span>,<br>
                    &nbsp;&nbsp;<span class="text-sky-300">'variant'</span>      =&gt; <span class="text-emerald-300">'default'</span>, <span class="text-slate-500">// default, glass, dark, flat</span><br>
                    &nbsp;&nbsp;<span class="text-sky-300">'headerAction'</span> =&gt; <span class="text-amber-300">ui_badge</span>(<span class="text-emerald-300">'Live'</span>, <span class="text-emerald-300">'brand'</span>),<br>
                    &nbsp;&nbsp;<span class="text-sky-300">'footer'</span>       =&gt; <span class="text-emerald-300">'&lt;span class="text-xs text-slate-400"&gt;Footer slot&lt;/span&gt;'</span>,<br>
                    ]) <span class="text-brand-400">?&gt;</span>
                </div>
            </section>

            <!-- ========================================== -->
            <!-- 8. ALERTS & NOTIFICATIONS                  -->
            <!-- ========================================== -->
            <section id="comp-alerts" class="rounded-card bg-white border border-slate-200/80 p-6 sm:p-8 space-y-6 scroll-mt-24" x-show="matchesSearch('Alerts & Notifications', 'alert banner dismiss success warning danger info')">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-2">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-brand-500"></span>
                            <h2 class="text-xl font-semibold text-slate-900 tracking-tight">Alerts & Notification Banners</h2>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">Semantic contextual banners with smooth Alpine.js dismiss animations.</p>
                    </div>
                    <button 
                        type="button" 
                        @click="copyCode('<?= addslashes("<?= ui_alert('Operation succeeded.', 'success', ['title' => 'Success', 'dismissible' => true]) ?>") ?>', 'code-alert')" 
                        class="px-2.5 py-1 rounded-btn bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-mono transition apple-tap flex items-center gap-1.5 self-start">
                        <i class="ph" :class="copiedSnippet === 'code-alert' ? 'ph-check text-emerald-600' : 'ph-copy'"></i>
                        <span x-text="copiedSnippet === 'code-alert' ? 'Copied!' : 'Copy Code'">Copy Code</span>
                    </button>
                </div>

                <div class="space-y-3">
                    <?= ui_alert('Your database changes and product records have been successfully saved.', 'success', [
                        'title'       => 'Changes Published',
                        'dismissible' => true,
                    ]) ?>

                    <?= ui_alert('Please verify your environment credentials before deploying to staging.', 'warning', [
                        'title'       => 'Connection Notice',
                        'dismissible' => true,
                    ]) ?>

                    <?= ui_alert('An unexpected database deadlock occurred. The transaction was rolled back.', 'danger', [
                        'title'       => 'Execution Error',
                        'dismissible' => true,
                    ]) ?>

                    <?= ui_alert('A new version of Vanilla PHP UI Starter (v2.0) is ready for download.', 'info', [
                        'title'       => 'System Update',
                        'dismissible' => true,
                    ]) ?>
                </div>

                <!-- Code Example Display -->
                <div class="rounded-btn bg-slate-900 text-slate-200 p-4 font-mono text-xs overflow-x-auto">
                    <span class="text-brand-400">&lt;?=</span> <span class="text-amber-300">ui_alert</span>(<span class="text-emerald-300">'Your profile was updated.'</span>, <span class="text-emerald-300">'success'</span>, [<br>
                    &nbsp;&nbsp;<span class="text-sky-300">'title'</span>       =&gt; <span class="text-emerald-300">'Success Notice'</span>,<br>
                    &nbsp;&nbsp;<span class="text-sky-300">'dismissible'</span> =&gt; <span class="text-rose-400">true</span>, <span class="text-slate-500">// enables smooth close button</span><br>
                    ]) <span class="text-brand-400">?&gt;</span>
                </div>
            </section>

            <!-- ========================================== -->
            <!-- 9. METRICS & STAT CARDS                    -->
            <!-- ========================================== -->
            <section id="comp-stat-cards" class="rounded-card bg-white border border-slate-200/80 p-6 sm:p-8 space-y-6 scroll-mt-24" x-show="matchesSearch('Stat Cards & Metrics', 'stat card metric trend health kpi value revenue')">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-2">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-brand-500"></span>
                            <h2 class="text-xl font-semibold text-slate-900 tracking-tight">Metrics & Dashboard Stat Cards</h2>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">Apple Health-inspired metrics with trend pills, subtext, and structured icon themes.</p>
                    </div>
                    <button 
                        type="button" 
                        @click="copyCode('<?= addslashes("<?= ui_stat_card('Monthly Revenue', 'Rp 84.5M', ['icon' => 'currency-dollar', 'trend' => '+24.5%', 'trendType' => 'up']) ?>") ?>', 'code-stat')" 
                        class="px-2.5 py-1 rounded-btn bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-mono transition apple-tap flex items-center gap-1.5 self-start">
                        <i class="ph" :class="copiedSnippet === 'code-stat' ? 'ph-check text-emerald-600' : 'ph-copy'"></i>
                        <span x-text="copiedSnippet === 'code-stat' ? 'Copied!' : 'Copy Code'">Copy Code</span>
                    </button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <?= ui_stat_card('Active Users', '14,820', [
                        'icon'      => 'users',
                        'trend'     => '+18.2%',
                        'trendType' => 'up',
                        'subtitle'  => 'vs. previous month',
                    ]) ?>

                    <?= ui_stat_card('Server TTFB', '32ms', [
                        'icon'        => 'lightning',
                        'iconVariant' => 'amber',
                        'trend'       => '-42%',
                        'trendType'   => 'up',
                        'subtitle'    => 'Ultra-fast PHP 8 execution',
                    ]) ?>

                    <?= ui_stat_card('Monthly Revenue', 'Rp 84.5M', [
                        'icon'        => 'currency-dollar',
                        'iconVariant' => 'brand',
                        'trend'       => '+24.5%',
                        'trendType'   => 'up',
                        'subtitle'    => 'Direct checkout store',
                    ]) ?>

                    <?= ui_stat_card('Memory Footprint', '1.8 MB', [
                        'icon'        => 'cpu',
                        'iconVariant' => 'slate',
                        'trend'       => 'Optimal',
                        'trendType'   => 'neutral',
                        'subtitle'    => 'Zero framework overhead',
                    ]) ?>
                </div>

                <!-- Code Example Display -->
                <div class="rounded-btn bg-slate-900 text-slate-200 p-4 font-mono text-xs overflow-x-auto">
                    <span class="text-brand-400">&lt;?=</span> <span class="text-amber-300">ui_stat_card</span>(<span class="text-emerald-300">'Total Orders'</span>, <span class="text-emerald-300">'1,248'</span>, [<br>
                    &nbsp;&nbsp;<span class="text-sky-300">'icon'</span>        =&gt; <span class="text-emerald-300">'shopping-bag'</span>,<br>
                    &nbsp;&nbsp;<span class="text-sky-300">'iconVariant'</span> =&gt; <span class="text-emerald-300">'brand'</span>, <span class="text-slate-500">// brand, emerald, amber, rose, slate</span><br>
                    &nbsp;&nbsp;<span class="text-sky-300">'trend'</span>       =&gt; <span class="text-emerald-300">'+12.4%'</span>,<br>
                    &nbsp;&nbsp;<span class="text-sky-300">'trendType'</span>   =&gt; <span class="text-emerald-300">'up'</span>,     <span class="text-slate-500">// up, down, neutral</span><br>
                    &nbsp;&nbsp;<span class="text-sky-300">'subtitle'</span>    =&gt; <span class="text-emerald-300">'vs last week'</span>,<br>
                    ]) <span class="text-brand-400">?&gt;</span>
                </div>
            </section>

            <!-- ========================================== -->
            <!-- 10. AVATARS & PRESENCE                     -->
            <!-- ========================================== -->
            <section id="comp-avatars" class="rounded-card bg-white border border-slate-200/80 p-6 sm:p-8 space-y-6 scroll-mt-24" x-show="matchesSearch('Avatars & Presence Stacks', 'avatar user presence online busy stack icon box')">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-2">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-brand-500"></span>
                            <h2 class="text-xl font-semibold text-slate-900 tracking-tight">Avatars, Presence & Avatar Stacks</h2>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">Initials generator, live status presence dots, and overlapping avatar stack groups.</p>
                    </div>
                    <button 
                        type="button" 
                        @click="copyCode('<?= addslashes("<?= ui_avatar('Alexander Brody', ['size' => 'lg', 'status' => 'online']) ?>") ?>', 'code-avatar')" 
                        class="px-2.5 py-1 rounded-btn bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-mono transition apple-tap flex items-center gap-1.5 self-start">
                        <i class="ph" :class="copiedSnippet === 'code-avatar' ? 'ph-check text-emerald-600' : 'ph-copy'"></i>
                        <span x-text="copiedSnippet === 'code-avatar' ? 'Copied!' : 'Copy Code'">Copy Code</span>
                    </button>
                </div>

                <div class="flex flex-wrap items-center justify-between gap-6">
                    <!-- Presence Demo -->
                    <div class="space-y-2">
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-400 block">Avatars with Presence Status</span>
                        <div class="flex items-center gap-3">
                            <?= ui_avatar('Alexander', ['size' => 'lg', 'status' => 'online']) ?>
                            <?= ui_avatar('Sarah Chen', ['size' => 'lg', 'status' => 'busy']) ?>
                            <?= ui_avatar('Marcus Brody', ['size' => 'lg', 'status' => 'away']) ?>
                            <?= ui_avatar('Elena Rostova', ['size' => 'lg', 'status' => 'offline']) ?>
                        </div>
                    </div>

                    <!-- Avatar Group Stack -->
                    <div class="space-y-2">
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-400 block">Overlapping Stack (Max 4 + Overflow)</span>
                        <div>
                            <?= ui_avatar_group(['Alex', 'Sarah', 'Marcus', 'Elena', 'David', 'Rachel'], ['size' => 'md', 'max' => 4]) ?>
                        </div>
                    </div>

                    <!-- Icon Box Primitives -->
                    <div class="space-y-2">
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-400 block">Icon Box Primitives</span>
                        <div class="flex items-center gap-2">
                            <?= ui_icon_box('sparkle', 'brand', ['size' => 'md']) ?>
                            <?= ui_icon_box('shield-check', 'emerald', ['size' => 'md']) ?>
                            <?= ui_icon_box('lightning', 'amber', ['size' => 'md']) ?>
                            <?= ui_icon_box('trash', 'rose', ['size' => 'md']) ?>
                        </div>
                    </div>
                </div>

                <!-- Code Example Display -->
                <div class="rounded-btn bg-slate-900 text-slate-200 p-4 font-mono text-xs overflow-x-auto">
                    <span class="text-slate-500">// Single avatar with status dot</span><br>
                    <span class="text-brand-400">&lt;?=</span> <span class="text-amber-300">ui_avatar</span>(<span class="text-emerald-300">'Sarah Chen'</span>, [<span class="text-sky-300">'size'</span> =&gt; <span class="text-emerald-300">'lg'</span>, <span class="text-sky-300">'status'</span> =&gt; <span class="text-emerald-300">'online'</span>]) <span class="text-brand-400">?&gt;</span><br><br>
                    <span class="text-slate-500">// Stack of avatars with +N counter</span><br>
                    <span class="text-brand-400">&lt;?=</span> <span class="text-amber-300">ui_avatar_group</span>([<span class="text-emerald-300">'Alex'</span>, <span class="text-emerald-300">'Sarah'</span>, <span class="text-emerald-300">'Marcus'</span>, <span class="text-emerald-300">'Elena'</span>, <span class="text-emerald-300">'David'</span>], [<span class="text-sky-300">'size'</span> =&gt; <span class="text-emerald-300">'md'</span>, <span class="text-sky-300">'max'</span> =&gt; <span class="text-amber-300">3</span>]) <span class="text-brand-400">?&gt;</span>
                </div>
            </section>

            <!-- ========================================== -->
            <!-- 11. TABS & SEGMENTED CONTROL               -->
            <!-- ========================================== -->
            <section id="comp-tabs" class="rounded-card bg-white border border-slate-200/80 p-6 sm:p-8 space-y-6 scroll-mt-24" x-show="matchesSearch('Tabs & Segmented Control', 'segmented control tabs switch model alpine')">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-2">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-brand-500"></span>
                            <h2 class="text-xl font-semibold text-slate-900 tracking-tight">Tabs & Segmented Controls</h2>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">Apple-style tactile sliding segmented control bound to Alpine.js state.</p>
                    </div>
                    <button 
                        type="button" 
                        @click="copyCode('<?= addslashes("<?= ui_segmented_control('activeTab', [['key' => 'tab1', 'label' => 'Tab 1', 'icon' => 'sparkle'], ['key' => 'tab2', 'label' => 'Tab 2', 'icon' => 'gear']]) ?>") ?>', 'code-tabs')" 
                        class="px-2.5 py-1 rounded-btn bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-mono transition apple-tap flex items-center gap-1.5 self-start">
                        <i class="ph" :class="copiedSnippet === 'code-tabs' ? 'ph-check text-emerald-600' : 'ph-copy'"></i>
                        <span x-text="copiedSnippet === 'code-tabs' ? 'Copied!' : 'Copy Code'">Copy Code</span>
                    </button>
                </div>

                <div class="space-y-4">
                    <div class="flex flex-wrap items-center gap-4">
                        <?= ui_segmented_control('activeTabDemo', [
                            ['key' => 'daily',   'label' => 'Daily Stats',   'icon' => 'calendar'],
                            ['key' => 'weekly',  'label' => 'Weekly View',  'icon' => 'calendar-blank'],
                            ['key' => 'monthly', 'label' => 'Monthly Trends', 'icon' => 'chart-line'],
                        ]) ?>
                    </div>

                    <!-- Live tab panel state preview -->
                    <div class="p-5 rounded-btn bg-slate-50 border border-slate-200/70 text-xs">
                        <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block mb-1">Live Reactive State (Bound to $xModel)</span>
                        <div x-show="activeTabDemo === 'daily'" class="text-slate-800 font-semibold flex items-center gap-2">
                            <i class="ph ph-calendar text-brand-600 text-base"></i>
                            <span>Showing Daily Performance metrics: 148 new orders recorded today.</span>
                        </div>
                        <div x-show="activeTabDemo === 'weekly'" class="text-slate-800 font-semibold flex items-center gap-2">
                            <i class="ph ph-calendar-blank text-brand-600 text-base"></i>
                            <span>Showing Weekly Performance: Revenue grew +18.2% this week.</span>
                        </div>
                        <div x-show="activeTabDemo === 'monthly'" class="text-slate-800 font-semibold flex items-center gap-2">
                            <i class="ph ph-chart-line text-brand-600 text-base"></i>
                            <span>Showing Monthly Trends: Total revenue Rp 84.5M across all channels.</span>
                        </div>
                    </div>
                </div>

                <!-- Code Example Display -->
                <div class="rounded-btn bg-slate-900 text-slate-200 p-4 font-mono text-xs overflow-x-auto">
                    <span class="text-brand-400">&lt;?=</span> <span class="text-amber-300">ui_segmented_control</span>(<span class="text-emerald-300">'selectedFilter'</span>, [<br>
                    &nbsp;&nbsp;[<span class="text-sky-300">'key'</span> =&gt; <span class="text-emerald-300">'all'</span>,       <span class="text-sky-300">'label'</span> =&gt; <span class="text-emerald-300">'All Items'</span>,   <span class="text-sky-300">'icon'</span> =&gt; <span class="text-emerald-300">'squares-four'</span>],<br>
                    &nbsp;&nbsp;[<span class="text-sky-300">'key'</span> =&gt; <span class="text-emerald-300">'active'</span>,    <span class="text-sky-300">'label'</span> =&gt; <span class="text-emerald-300">'Active Only'</span>, <span class="text-sky-300">'icon'</span> =&gt; <span class="text-emerald-300">'check-circle'</span>],<br>
                    &nbsp;&nbsp;[<span class="text-sky-300">'key'</span> =&gt; <span class="text-emerald-300">'archived'</span>,  <span class="text-sky-300">'label'</span> =&gt; <span class="text-emerald-300">'Archived'</span>,    <span class="text-sky-300">'icon'</span> =&gt; <span class="text-emerald-300">'archive'</span>],<br>
                    ], [<span class="text-sky-300">'size'</span> =&gt; <span class="text-emerald-300">'md'</span>]) <span class="text-brand-400">?&gt;</span>
                </div>
            </section>

            <!-- ========================================== -->
            <!-- 12. BREADCRUMBS                            -->
            <!-- ========================================== -->
            <section id="comp-breadcrumbs" class="rounded-card bg-white border border-slate-200/80 p-6 sm:p-8 space-y-6 scroll-mt-24" x-show="matchesSearch('Breadcrumbs & Trails', 'breadcrumb navigation trail path caret')">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-2">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-brand-500"></span>
                            <h2 class="text-xl font-semibold text-slate-900 tracking-tight">Breadcrumbs & Navigation Trails</h2>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">Hierarchical wayfinding trail with home shortcut, optical carets, and active leaf state.</p>
                    </div>
                    <button 
                        type="button" 
                        @click="copyCode('<?= addslashes("<?= ui_breadcrumb([['label' => 'Products', 'href' => '/products.php'], ['label' => 'Audio', 'href' => '/category.php?id=1'], ['label' => 'AirPods Max', 'href' => null]]) ?>") ?>', 'code-crumb')" 
                        class="px-2.5 py-1 rounded-btn bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-mono transition apple-tap flex items-center gap-1.5 self-start">
                        <i class="ph" :class="copiedSnippet === 'code-crumb' ? 'ph-check text-emerald-600' : 'ph-copy'"></i>
                        <span x-text="copiedSnippet === 'code-crumb' ? 'Copied!' : 'Copy Code'">Copy Code</span>
                    </button>
                </div>

                <div class="rounded-card border border-slate-200/70 overflow-hidden">
                    <?= ui_breadcrumb([
                        ['label' => 'Store Catalog', 'href' => base_url('demo.php'), 'icon' => 'shopping-bag'],
                        ['label' => 'Electronics & Audio', 'href' => base_url('demo.php?category_id=1'), 'icon' => 'headphones'],
                        ['label' => 'AirPods Max (Space Gray)', 'href' => null],
                    ]) ?>
                </div>

                <!-- Code Example Display -->
                <div class="rounded-btn bg-slate-900 text-slate-200 p-4 font-mono text-xs overflow-x-auto">
                    <span class="text-brand-400">&lt;?=</span> <span class="text-amber-300">ui_breadcrumb</span>([<br>
                    &nbsp;&nbsp;[<span class="text-sky-300">'label'</span> =&gt; <span class="text-emerald-300">'Products'</span>, <span class="text-sky-300">'href'</span> =&gt; <span class="text-emerald-300">'/products.php'</span>, <span class="text-sky-300">'icon'</span> =&gt; <span class="text-emerald-300">'package'</span>],<br>
                    &nbsp;&nbsp;[<span class="text-sky-300">'label'</span> =&gt; <span class="text-emerald-300">'Headphones'</span>, <span class="text-sky-300">'href'</span> =&gt; <span class="text-emerald-300">'/category.php?id=2'</span>],<br>
                    &nbsp;&nbsp;[<span class="text-sky-300">'label'</span> =&gt; <span class="text-emerald-300">'Product Detail'</span>, <span class="text-sky-300">'href'</span> =&gt; <span class="text-rose-400">null</span>],<br>
                    ]) <span class="text-brand-400">?&gt;</span>
                </div>
            </section>

            <!-- ========================================== -->
            <!-- 13. MODALS & SHEET OVERLAYS                -->
            <!-- ========================================== -->
            <section id="comp-modals" class="rounded-card bg-white border border-slate-200/80 p-6 sm:p-8 space-y-6 scroll-mt-24" x-show="matchesSearch('Modal Sheets & Dialogs', 'modal dialog sheet backdrop blur overlay')">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-2">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-brand-500"></span>
                            <h2 class="text-xl font-semibold text-slate-900 tracking-tight">Modal Sheets & Dialog Overlays</h2>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">Apple Sheet modal with translucent backdrop blur, spring scaling, and Escape key listener.</p>
                    </div>
                    <button 
                        type="button" 
                        @click="copyCode('<?= addslashes("<?= ui_modal('showModal', 'Modal Title', '<p>Modal Body</p>', ['isVar' => true]) ?>") ?>', 'code-modal')" 
                        class="px-2.5 py-1 rounded-btn bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-mono transition apple-tap flex items-center gap-1.5 self-start">
                        <i class="ph" :class="copiedSnippet === 'code-modal' ? 'ph-check text-emerald-600' : 'ph-copy'"></i>
                        <span x-text="copiedSnippet === 'code-modal' ? 'Copied!' : 'Copy Code'">Copy Code</span>
                    </button>
                </div>

                <div class="p-6 rounded-card bg-slate-50 border border-slate-200/70 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <span class="font-semibold text-sm text-slate-900 block">Interactive Live Sheet Dialog</span>
                        <p class="text-xs text-slate-500 mt-0.5">Click the trigger below to test the spring transition and backdrop blur.</p>
                    </div>

                    <button 
                        type="button" 
                        @click="showSampleModal = true" 
                        class="px-4 py-2.5 rounded-btn bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold transition apple-tap flex items-center gap-2">
                        <?= ui_icon('browsers', 'text-sm') ?>
                        <span>Launch Sheet Modal</span>
                    </button>
                </div>

                <!-- Code Example Display -->
                <div class="rounded-btn bg-slate-900 text-slate-200 p-4 font-mono text-xs overflow-x-auto">
                    <span class="text-brand-400">&lt;?=</span> <span class="text-amber-300">ui_modal</span>(<span class="text-emerald-300">'showSampleModal'</span>, <span class="text-emerald-300">'Confirm Action'</span>, <span class="text-emerald-300">'&lt;p&gt;Are you sure?&lt;/p&gt;'</span>, [<br>
                    &nbsp;&nbsp;<span class="text-sky-300">'isVar'</span>    =&gt; <span class="text-rose-400">true</span>, <span class="text-slate-500">// binds to Alpine boolean variable directly</span><br>
                    &nbsp;&nbsp;<span class="text-sky-300">'subtitle'</span> =&gt; <span class="text-emerald-300">'Destructive confirmation'</span>,<br>
                    &nbsp;&nbsp;<span class="text-sky-300">'icon'</span>     =&gt; <span class="text-emerald-300">'warning-circle'</span>,<br>
                    &nbsp;&nbsp;<span class="text-sky-300">'footer'</span>   =&gt; <span class="text-emerald-300">'&lt;button @click="showSampleModal = false"&gt;Cancel&lt;/button&gt;'</span>,<br>
                    ]) <span class="text-brand-400">?&gt;</span>
                </div>
            </section>

            <!-- ========================================== -->
            <!-- 14. EMPTY STATES & PLACEHOLDERS            -->
            <!-- ========================================== -->
            <section id="comp-empty-states" class="rounded-card bg-white border border-slate-200/80 p-6 sm:p-8 space-y-6 scroll-mt-24" x-show="matchesSearch('Empty States & Placeholders', 'empty state placeholder zero data no results')">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-2">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-brand-500"></span>
                            <h2 class="text-xl font-semibold text-slate-900 tracking-tight">Empty States & Placeholders</h2>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">Placeholder surfaces for zero data lists, empty shopping carts, or unfound search results.</p>
                    </div>
                    <button 
                        type="button" 
                        @click="copyCode('<?= addslashes("<?= ui_empty_state('No Orders Found', 'You haven\'t placed any orders yet.', ['icon' => 'package', 'buttonText' => 'Start Shopping', 'buttonHref' => '/demo.php']) ?>") ?>', 'code-empty')" 
                        class="px-2.5 py-1 rounded-btn bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-mono transition apple-tap flex items-center gap-1.5 self-start">
                        <i class="ph" :class="copiedSnippet === 'code-empty' ? 'ph-check text-emerald-600' : 'ph-copy'"></i>
                        <span x-text="copiedSnippet === 'code-empty' ? 'Copied!' : 'Copy Code'">Copy Code</span>
                    </button>
                </div>

                <div class="rounded-card border border-slate-200/70 p-4 bg-slate-50">
                    <?= ui_empty_state(
                        'No Matching Results',
                        'We couldn\'t find any records matching your active filters. Try resetting the search query or exploring popular categories.',
                        [
                            'icon'        => 'magnifying-glass',
                            'buttonText'  => 'Reset Filter Search',
                            'buttonHref'  => '#',
                            'buttonIcon'  => 'arrow-clockwise',
                        ]
                    ) ?>
                </div>

                <!-- Code Example Display -->
                <div class="rounded-btn bg-slate-900 text-slate-200 p-4 font-mono text-xs overflow-x-auto">
                    <span class="text-brand-400">&lt;?=</span> <span class="text-amber-300">ui_empty_state</span>(<span class="text-emerald-300">'Your Cart is Empty'</span>, <span class="text-emerald-300">'Explore our showcase products to add items.'</span>, [<br>
                    &nbsp;&nbsp;<span class="text-sky-300">'icon'</span>       =&gt; <span class="text-emerald-300">'shopping-bag'</span>,<br>
                    &nbsp;&nbsp;<span class="text-sky-300">'buttonText'</span> =&gt; <span class="text-emerald-300">'Browse Catalog'</span>,<br>
                    &nbsp;&nbsp;<span class="text-sky-300">'buttonHref'</span> =&gt; <span class="text-emerald-300">'/demo.php'</span>,<br>
                    &nbsp;&nbsp;<span class="text-sky-300">'buttonIcon'</span> =&gt; <span class="text-emerald-300">'arrow-right'</span>,<br>
                    ]) <span class="text-brand-400">?&gt;</span>
                </div>
            </section>

            <!-- ========================================== -->
            <!-- 15. PRODUCT CARDS & COMMERCE               -->
            <!-- ========================================== -->
            <section id="comp-product-cards" class="rounded-card bg-white border border-slate-200/80 p-6 sm:p-8 space-y-6 scroll-mt-24" x-show="matchesSearch('Product Cards & Commerce', 'product commerce price discount cart store')">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-2">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-brand-500"></span>
                            <h2 class="text-xl font-semibold text-slate-900 tracking-tight">Product Cards & Commerce Primitives</h2>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">E-commerce product cards with discount badges, price formatting, stock status, and add-to-cart.</p>
                    </div>
                    <button 
                        type="button" 
                        @click="copyCode('<?= addslashes("<?= ui_product_card(\$product) ?>") ?>', 'code-prod')" 
                        class="px-2.5 py-1 rounded-btn bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-mono transition apple-tap flex items-center gap-1.5 self-start">
                        <i class="ph" :class="copiedSnippet === 'code-prod' ? 'ph-check text-emerald-600' : 'ph-copy'"></i>
                        <span x-text="copiedSnippet === 'code-prod' ? 'Copied!' : 'Copy Code'">Copy Code</span>
                    </button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?= ui_product_card($sampleProduct) ?>

                    <?= ui_product_card([
                        'id'            => 102,
                        'name'          => 'Apple Studio Display (27-inch 5K Retina)',
                        'category_name' => 'Monitors & Displays',
                        'price'         => 28999000,
                        'promo_price'   => null,
                        'stock'         => 3,
                        'is_featured'   => 0,
                        'image'         => 'studio-display.jpg',
                    ]) ?>

                    <?= ui_product_card([
                        'id'            => 103,
                        'name'          => 'Magic Keyboard with Touch ID',
                        'category_name' => 'Accessories',
                        'price'         => 2499000,
                        'promo_price'   => null,
                        'stock'         => 0,
                        'is_featured'   => 0,
                        'image'         => 'magic-keyboard.jpg',
                    ]) ?>
                </div>

                <!-- Code Example Display -->
                <div class="rounded-btn bg-slate-900 text-slate-200 p-4 font-mono text-xs overflow-x-auto">
                    <span class="text-brand-400">&lt;?=</span> <span class="text-amber-300">ui_product_card</span>([<br>
                    &nbsp;&nbsp;<span class="text-sky-300">'id'</span>            =&gt; <span class="text-amber-300">1</span>,<br>
                    &nbsp;&nbsp;<span class="text-sky-300">'name'</span>          =&gt; <span class="text-emerald-300">'Product Name'</span>,<br>
                    &nbsp;&nbsp;<span class="text-sky-300">'category_name'</span> =&gt; <span class="text-emerald-300">'Category'</span>,<br>
                    &nbsp;&nbsp;<span class="text-sky-300">'price'</span>         =&gt; <span class="text-amber-300">500000</span>,<br>
                    &nbsp;&nbsp;<span class="text-sky-300">'promo_price'</span>   =&gt; <span class="text-amber-300">450000</span>, <span class="text-slate-500">// optional promo price</span><br>
                    &nbsp;&nbsp;<span class="text-sky-300">'stock'</span>         =&gt; <span class="text-amber-300">12</span>,<br>
                    &nbsp;&nbsp;<span class="text-sky-300">'image'</span>         =&gt; <span class="text-emerald-300">'sample.jpg'</span>,<br>
                    ]) <span class="text-brand-400">?&gt;</span>
                </div>
            </section>

            <!-- ========================================== -->
            <!-- 16. PHOSPHOR ICONS HELPER                  -->
            <!-- ========================================== -->
            <section id="comp-icons" class="rounded-card bg-white border border-slate-200/80 p-6 sm:p-8 space-y-6 scroll-mt-24" x-show="matchesSearch('Phosphor Icons Helper', 'icon phosphor font svg box glyph')">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-2">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-brand-500"></span>
                            <h2 class="text-xl font-semibold text-slate-900 tracking-tight">Phosphor Icons Integration</h2>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">High-clarity optical icon helper with weights (regular, bold, fill, duotone) and color classes.</p>
                    </div>
                    <button 
                        type="button" 
                        @click="copyCode('<?= addslashes("<?= ui_icon('sparkle', 'text-brand-600 text-lg') ?>") ?>', 'code-icon')" 
                        class="px-2.5 py-1 rounded-btn bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-mono transition apple-tap flex items-center gap-1.5 self-start">
                        <i class="ph" :class="copiedSnippet === 'code-icon' ? 'ph-check text-emerald-600' : 'ph-copy'"></i>
                        <span x-text="copiedSnippet === 'code-icon' ? 'Copied!' : 'Copy Code'">Copy Code</span>
                    </button>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-3 text-center">
                    <div class="p-3.5 rounded-btn bg-slate-50 border border-slate-200/70 flex flex-col items-center gap-1.5">
                        <?= ui_icon('sparkle', 'text-2xl text-brand-600') ?>
                        <span class="text-[11px] font-mono text-slate-600">sparkle</span>
                    </div>
                    <div class="p-3.5 rounded-btn bg-slate-50 border border-slate-200/70 flex flex-col items-center gap-1.5">
                        <?= ui_icon('shield-check', 'text-2xl text-emerald-600') ?>
                        <span class="text-[11px] font-mono text-slate-600">shield-check</span>
                    </div>
                    <div class="p-3.5 rounded-btn bg-slate-50 border border-slate-200/70 flex flex-col items-center gap-1.5">
                        <?= ui_icon('lightning', 'text-2xl text-amber-500') ?>
                        <span class="text-[11px] font-mono text-slate-600">lightning</span>
                    </div>
                    <div class="p-3.5 rounded-btn bg-slate-50 border border-slate-200/70 flex flex-col items-center gap-1.5">
                        <?= ui_icon('shopping-bag', 'text-2xl text-indigo-600') ?>
                        <span class="text-[11px] font-mono text-slate-600">shopping-bag</span>
                    </div>
                    <div class="p-3.5 rounded-btn bg-slate-50 border border-slate-200/70 flex flex-col items-center gap-1.5">
                        <?= ui_icon('palette', 'text-2xl text-rose-500') ?>
                        <span class="text-[11px] font-mono text-slate-600">palette</span>
                    </div>
                    <div class="p-3.5 rounded-btn bg-slate-50 border border-slate-200/70 flex flex-col items-center gap-1.5">
                        <?= ui_icon('gear', 'text-2xl text-slate-600') ?>
                        <span class="text-[11px] font-mono text-slate-600">gear</span>
                    </div>
                </div>

                <!-- Code Example Display -->
                <div class="rounded-btn bg-slate-900 text-slate-200 p-4 font-mono text-xs overflow-x-auto">
                    <span class="text-brand-400">&lt;?=</span> <span class="text-amber-300">ui_icon</span>(<span class="text-emerald-300">'sparkle'</span>, <span class="text-emerald-300">'text-brand-600 text-lg'</span>) <span class="text-brand-400">?&gt;</span>
                </div>
            </section>

        </div>
    </div>

    <!-- Live Demo Sheet Modal -->
    <?= ui_modal('showSampleModal', 'Apple Sheet Modal Dialog', '
        <p class="text-slate-600 text-xs sm:text-sm leading-relaxed mb-4">
            This modal dialog demonstrates the Apple design sheet aesthetic with translucent backdrop blur, smooth spring scaling, keyboard Escape listener, and accessible focus management.
        </p>
        <div class="p-4 rounded-btn bg-brand-50 border border-brand-200/80 text-brand-900 text-xs flex items-center gap-2.5">
            ' . ui_icon('sparkle', 'text-brand-600 text-base') . '
            <span>100% Vanilla PHP + Alpine.js zero-bloat architecture.</span>
        </div>
    ', [
        'isVar'       => true,
        'subtitle'    => 'Fluid dialog component primitive',
        'icon'        => 'browsers',
        'footer'      => '<button type="button" @click="showSampleModal = false" class="px-4 py-2 rounded-btn bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-semibold transition apple-tap">Close</button> <button type="button" @click="showSampleModal = false" class="px-4 py-2 rounded-btn bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold transition apple-tap">Confirm Action</button>',
    ]) ?>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
