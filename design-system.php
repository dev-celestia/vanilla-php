<?php
/**
 * Public Design System & UI Component Library Explorer
 *
 * An interactive living style guide, token explorer, and component catalog.
 * Built with Apple Human Interface principles, zero bloat, and pure Vanilla PHP.
 */
$active_nav = 'design_system';
$page_title = 'Design System & Component Library - Vanilla PHP UI';
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/helpers/format.php';

$activeTheme = get_active_theme();
$palettes = get_theme_color_palettes();
$radiuses = get_theme_radius_presets();

require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Header Banner (Apple Glass Material, Crisp Typography) -->
<section class="relative bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 text-white overflow-hidden py-16 lg:py-24 border-b border-slate-800">
    <div class="absolute -top-40 -right-40 w-[600px] h-[600px] bg-brand-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-40 -left-40 w-[600px] h-[600px] bg-brand-400/5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="max-w-3xl">
            <div class="flex flex-wrap items-center gap-2 mb-4">
                <?= ui_badge('Apple Human Interface', 'brand', ['dot' => true, 'pulse' => true]) ?>
                <?= ui_badge('Design Tokens v1.0', 'neutral') ?>
                <?= ui_badge('Zero Dependencies', 'success') ?>
            </div>
            
            <h1 class="text-3xl sm:text-5xl font-semibold tracking-tight leading-tight">
                Design System & <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-300 to-brand-500">Component Library</span>
            </h1>
            
            <p class="text-slate-300 text-sm sm:text-base mt-4 leading-relaxed">
                Explore centralized design tokens, color palettes, corner radius presets, typography scales, and fluid UI component primitives for Vanilla PHP. Click on any token to preview it live across the entire interface.
            </p>

            <!-- Quick Jump Navigation -->
            <div class="flex flex-wrap gap-2 pt-6">
                <a href="#tokens" class="px-3.5 py-1.5 rounded-btn bg-slate-800/80 hover:bg-slate-700 text-white text-xs font-semibold border border-slate-700 transition apple-tap flex items-center gap-1.5">
                    <?= ui_icon('palette', 'text-brand-400 text-sm') ?>
                    <span>Design Tokens</span>
                </a>
                <a href="<?= base_url('components.php') ?>" class="px-3.5 py-1.5 rounded-btn bg-slate-800/80 hover:bg-slate-700 text-white text-xs font-semibold border border-slate-700 transition apple-tap flex items-center gap-1.5">
                    <?= ui_icon('squares-four', 'text-brand-400 text-sm') ?>
                    <span>Component Catalog (Dedicated Page)</span>
                </a>
                <a href="<?= base_url('demo.php') ?>" class="px-3.5 py-1.5 rounded-btn bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold border border-brand-500/30 transition apple-tap flex items-center gap-1.5">
                    <?= ui_icon('shopping-bag', 'text-sm') ?>
                    <span>Open Showcase Demo</span>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Main Design System Explorer Container -->
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-16" x-data="{ activeSection: 'tokens', copiedSnippet: null, showSampleModal: false }">

    <!-- Section 1: Active Configuration Bar -->
    <div class="rounded-card bg-white border border-slate-200/80 p-6">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div>
                <span class="text-[11px] font-semibold uppercase tracking-wider text-brand-600 block mb-1">Live Theme State</span>
                <h2 class="text-xl font-semibold text-slate-900 tracking-tight">Active Design Tokens</h2>
                <p class="text-xs text-slate-500 mt-0.5">These CSS variables dynamically control color schemes and geometry throughout the app.</p>
            </div>

            <!-- Active Token Chips -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <div class="p-3 rounded-btn bg-slate-50 border border-slate-200/70 text-center sm:text-left">
                    <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider block">Palette</span>
                    <span class="text-xs font-semibold text-slate-800 flex items-center justify-center sm:justify-start gap-1.5 mt-0.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-brand-600 inline-block"></span>
                        <?= $activeTheme['palette']['name'] ?>
                    </span>
                </div>

                <div class="p-3 rounded-btn bg-slate-50 border border-slate-200/70 text-center sm:text-left">
                    <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider block">Radius</span>
                    <span class="text-xs font-semibold text-slate-800 mt-0.5 block"><?= $activeTheme['radius']['name'] ?></span>
                </div>

                <div class="p-3 rounded-btn bg-slate-50 border border-slate-200/70 text-center sm:text-left">
                    <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider block">Shadow Policy</span>
                    <span class="text-xs font-semibold text-brand-600 mt-0.5 block">0px (Hairline Borders)</span>
                </div>

                <div class="p-3 rounded-btn bg-slate-50 border border-slate-200/70 text-center sm:text-left">
                    <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider block">Materials</span>
                    <span class="text-xs font-semibold text-slate-800 mt-0.5 block">Translucent Blur</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 2: Token Explorer (Colors & Radius) -->
    <section id="tokens" class="space-y-8 scroll-mt-24">
        <div>
            <span class="text-xs font-semibold uppercase tracking-wider text-brand-600 block">Design Tokens</span>
            <h2 class="text-2xl font-semibold text-slate-900 tracking-tight mt-1">Color Palettes & Corner Radius Presets</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-2xl">
                Choose a palette or corner radius preset to test how the entire design system adapts in real-time.
            </p>
        </div>

        <!-- Color Palettes Grid -->
        <div class="rounded-card bg-white border border-slate-200/80 p-6">
            <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                <?= ui_icon('paint-brush', 'text-brand-600 text-base') ?>
                <span>Primary Color Palettes (Click to switch live)</span>
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <?php foreach ($palettes as $key => $p): 
                    $isActive = ($activeTheme['color_key'] === $key);
                ?>
                <a href="?theme=<?= $key ?>&radius=<?= $activeTheme['radius_key'] ?>" 
                   class="p-4 rounded-card border transition-all apple-tap flex flex-col justify-between <?= $isActive ? 'border-brand-500 bg-brand-50/40 ring-2 ring-brand-500/20' : 'border-slate-200 hover:border-slate-300 bg-white hover:bg-slate-50/50' ?>">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-semibold text-xs text-slate-900"><?= $p['name'] ?></span>
                            <?php if ($isActive): ?>
                                <span class="px-2 py-0.5 rounded-full bg-brand-600 text-white text-[10px] font-semibold">Active</span>
                            <?php endif; ?>
                        </div>

                        <!-- 11-step Color Swatch Spectrum -->
                        <div class="grid grid-cols-11 h-6 rounded-btn overflow-hidden border border-black/10">
                            <div class="h-full" style="background-color: <?= $p['50'] ?>;" title="50: <?= $p['50'] ?>"></div>
                            <div class="h-full" style="background-color: <?= $p['100'] ?>;" title="100: <?= $p['100'] ?>"></div>
                            <div class="h-full" style="background-color: <?= $p['200'] ?>;" title="200: <?= $p['200'] ?>"></div>
                            <div class="h-full" style="background-color: <?= $p['300'] ?>;" title="300: <?= $p['300'] ?>"></div>
                            <div class="h-full" style="background-color: <?= $p['400'] ?>;" title="400: <?= $p['400'] ?>"></div>
                            <div class="h-full" style="background-color: <?= $p['500'] ?>;" title="500: <?= $p['500'] ?>"></div>
                            <div class="h-full" style="background-color: <?= $p['600'] ?>;" title="600: <?= $p['600'] ?>"></div>
                            <div class="h-full" style="background-color: <?= $p['700'] ?>;" title="700: <?= $p['700'] ?>"></div>
                            <div class="h-full" style="background-color: <?= $p['800'] ?>;" title="800: <?= $p['800'] ?>"></div>
                            <div class="h-full" style="background-color: <?= $p['900'] ?>;" title="900: <?= $p['900'] ?>"></div>
                            <div class="h-full" style="background-color: <?= $p['950'] ?>;" title="950: <?= $p['950'] ?>"></div>
                        </div>
                    </div>
                    
                    <div class="mt-3 flex items-center justify-between text-[11px] text-slate-400 font-mono">
                        <span>500: <?= $p['500'] ?></span>
                        <span>600: <?= $p['600'] ?></span>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Corner Radius Scale Grid -->
        <div class="rounded-card bg-white border border-slate-200/80 p-6">
            <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                <?= ui_icon('corners-out', 'text-brand-600 text-base') ?>
                <span>Corner Radius Scale (Click to switch live)</span>
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php foreach ($radiuses as $rKey => $r): 
                    $isRActive = ($activeTheme['radius_key'] === $rKey);
                ?>
                <a href="?radius=<?= $rKey ?>&theme=<?= $activeTheme['color_key'] ?>" 
                   class="p-4 rounded-card border transition-all apple-tap <?= $isRActive ? 'border-brand-500 bg-brand-50/40 ring-2 ring-brand-500/20' : 'border-slate-200 hover:border-slate-300 bg-white hover:bg-slate-50/50' ?>">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-semibold text-xs text-slate-900"><?= $r['name'] ?></span>
                        <?php if ($isRActive): ?>
                            <span class="px-2 py-0.5 rounded-full bg-brand-600 text-white text-[10px] font-semibold">Active</span>
                        <?php endif; ?>
                    </div>
                    <p class="text-[11px] text-slate-500 mb-3"><?= $r['description'] ?></p>

                    <!-- Radius visual preview box -->
                    <div class="flex items-center gap-2">
                        <div class="h-10 px-4 bg-brand-600 text-white text-xs font-semibold flex items-center justify-center border border-brand-500/20" style="border-radius: <?= $r['btn'] ?>;">
                            Button: <?= $r['btn'] ?>
                        </div>
                        <div class="h-10 px-4 bg-slate-100 border border-slate-200 text-slate-700 text-xs font-semibold flex items-center justify-center" style="border-radius: <?= $r['card'] ?>;">
                            Card: <?= $r['card'] ?>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Section 3: Component Catalog Showcase -->
    <section id="components" class="space-y-8 scroll-mt-24">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-brand-600 block">UI Primitives</span>
                <h2 class="text-2xl font-semibold text-slate-900 tracking-tight mt-1">Component Primitives & Live Sandbox</h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-2xl">
                    Pre-built, accessible, modular UI primitives designed for zero bloat and high performance.
                </p>
            </div>
            <a href="<?= base_url('components.php') ?>" class="px-4 py-2.5 rounded-btn bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold transition apple-tap flex items-center gap-2 self-start">
                <?= ui_icon('sidebar', 'text-sm') ?>
                <span>Open Dedicated Explorer with Sidebar</span>
                <?= ui_icon('arrow-right', 'text-xs') ?>
            </a>
        </div>

        <!-- 1. Buttons & Button Groups -->
        <div class="rounded-card bg-white border border-slate-200/80 p-6 space-y-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h3 class="text-base font-semibold text-slate-900 tracking-tight">Buttons & Action Groups</h3>
                    <p class="text-xs text-slate-500">Variants, sizes, icon slots, loading spinners, and segmented button groups.</p>
                </div>
                <code class="text-[11px] font-mono bg-slate-100 text-slate-600 px-2 py-1 rounded">ui_button()</code>
            </div>

            <!-- Variants Grid -->
            <div>
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block mb-3">Variants</span>
                <div class="flex flex-wrap items-center gap-3">
                    <?= ui_button('Primary Button', ['variant' => 'primary', 'icon' => 'sparkle']) ?>
                    <?= ui_button('Secondary', ['variant' => 'secondary', 'icon' => 'gear']) ?>
                    <?= ui_button('Subtle Brand', ['variant' => 'subtle']) ?>
                    <?= ui_button('Outline', ['variant' => 'outline', 'icon' => 'arrow-square-out']) ?>
                    <?= ui_button('Ghost Button', ['variant' => 'ghost']) ?>
                    <?= ui_button('Danger Action', ['variant' => 'danger', 'icon' => 'trash']) ?>
                    <?= ui_button('Glass Material', ['variant' => 'glass', 'icon' => 'drop']) ?>
                    <?= ui_button('Loading State', ['variant' => 'primary', 'loading' => true]) ?>
                </div>
            </div>

            <!-- Sizes Grid -->
            <div>
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block mb-3">Sizes</span>
                <div class="flex flex-wrap items-center gap-3">
                    <?= ui_button('Extra Small (xs)', ['size' => 'xs', 'icon' => 'check']) ?>
                    <?= ui_button('Small (sm)', ['size' => 'sm', 'icon' => 'check']) ?>
                    <?= ui_button('Medium (md)', ['size' => 'md', 'icon' => 'check']) ?>
                    <?= ui_button('Large (lg)', ['size' => 'lg', 'icon' => 'check']) ?>
                    <?= ui_button('Extra Large (xl)', ['size' => 'xl', 'icon' => 'check']) ?>
                </div>
            </div>

            <!-- Segmented Control / Button Groups -->
            <div>
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block mb-3">Segmented Control & Button Groups</span>
                <div class="flex flex-wrap items-center gap-4">
                    <?= ui_button_group([
                        ['label' => 'Daily', 'options' => ['variant' => 'secondary', 'size' => 'sm']],
                        ['label' => 'Weekly', 'options' => ['variant' => 'primary', 'size' => 'sm']],
                        ['label' => 'Monthly', 'options' => ['variant' => 'secondary', 'size' => 'sm']],
                        ['label' => 'Yearly', 'options' => ['variant' => 'secondary', 'size' => 'sm']],
                    ]) ?>

                    <?= ui_button_group([
                        ['label' => 'Grid View', 'options' => ['variant' => 'primary', 'size' => 'sm', 'icon' => 'squares-four']],
                        ['label' => 'List View', 'options' => ['variant' => 'secondary', 'size' => 'sm', 'icon' => 'list']],
                    ]) ?>
                </div>
            </div>
        </div>

        <!-- 2. Badges, Indicators & Status Chips -->
        <div class="rounded-card bg-white border border-slate-200/80 p-6 space-y-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h3 class="text-base font-semibold text-slate-900 tracking-tight">Badges & Live Status Indicators</h3>
                    <p class="text-xs text-slate-500">Pill badges, live pulse dots, icons, and automated status mappings.</p>
                </div>
                <code class="text-[11px] font-mono bg-slate-100 text-slate-600 px-2 py-1 rounded">ui_badge() / ui_status_badge()</code>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <?= ui_badge('Brand Badge', 'brand', ['dot' => true]) ?>
                <?= ui_badge('Live Status', 'brand', ['dot' => true, 'pulse' => true]) ?>
                <?= ui_badge('Neutral Chip', 'neutral') ?>
                <?= ui_badge('Active / Published', 'success', ['dot' => true]) ?>
                <?= ui_badge('Pending Review', 'warning', ['dot' => true]) ?>
                <?= ui_badge('Rejected / Error', 'danger', ['dot' => true]) ?>
                <?= ui_badge('System Notice', 'info', ['icon' => 'info']) ?>
                <?= ui_badge('Dark Pill', 'dark', ['rounded' => 'full']) ?>
                <?= ui_badge('Glass Pill', 'glass', ['rounded' => 'full']) ?>
            </div>
        </div>

        <!-- 3. Cards & Surfaces -->
        <div class="rounded-card bg-white border border-slate-200/80 p-6 space-y-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h3 class="text-base font-semibold text-slate-900 tracking-tight">Surfaces & Modular Cards</h3>
                    <p class="text-xs text-slate-500">Apple hairline borders, header actions, glass depth, and footer slots.</p>
                </div>
                <code class="text-[11px] font-mono bg-slate-100 text-slate-600 px-2 py-1 rounded">ui_card()</code>
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
                    '<p class="text-xs text-slate-600 leading-relaxed">Translucent Apple material card with background blur and subtle ambient depth.</p>',
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
                    '<p class="text-xs text-slate-300 leading-relaxed">Dark-mode contrast card for high-priority sections or code terminals.</p>',
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
        </div>

        <!-- 4. Alerts & Notifications -->
        <div class="rounded-card bg-white border border-slate-200/80 p-6 space-y-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h3 class="text-base font-semibold text-slate-900 tracking-tight">Alerts & Notification Banners</h3>
                    <p class="text-xs text-slate-500">Contextual alerts with dismiss animations via Alpine.js.</p>
                </div>
                <code class="text-[11px] font-mono bg-slate-100 text-slate-600 px-2 py-1 rounded">ui_alert()</code>
            </div>

            <div class="space-y-3">
                <?= ui_alert('Your changes have been saved successfully to the database.', 'success', [
                    'title'       => 'Changes Published',
                    'dismissible' => true,
                ]) ?>

                <?= ui_alert('Please verify your connection settings before deploying to production.', 'warning', [
                    'title'       => 'Warning Notice',
                    'dismissible' => true,
                ]) ?>

                <?= ui_alert('An unexpected error occurred during database migration. Please try again.', 'danger', [
                    'title'       => 'Migration Error',
                    'dismissible' => true,
                ]) ?>

                <?= ui_alert('A new version of Vanilla PHP UI Starter (v2.0) is now available for download.', 'info', [
                    'title'       => 'Update Available',
                    'dismissible' => true,
                ]) ?>
            </div>
        </div>

        <!-- 5. Form Inputs, Selects & iOS Toggles -->
        <div class="rounded-card bg-white border border-slate-200/80 p-6 space-y-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h3 class="text-base font-semibold text-slate-900 tracking-tight">Form Controls & iOS Switches</h3>
                    <p class="text-xs text-slate-500">Inputs with optical icons, custom selects, textareas, and tactile iOS toggles.</p>
                </div>
                <code class="text-[11px] font-mono bg-slate-100 text-slate-600 px-2 py-1 rounded">ui_input() / ui_toggle()</code>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 items-start">
                <?= ui_input('sample_search', [
                    'label'       => 'Search Query',
                    'placeholder' => 'Type to search components...',
                    'icon'        => 'magnifying-glass',
                    'helper'      => 'Instant search with keyboard focus',
                ]) ?>

                <?= ui_select('sample_category', [
                    'Frontend' => ['btn' => 'Buttons & Links', 'inputs' => 'Form Inputs'],
                    'Backend'  => ['db' => 'MySQL PDO', 'auth' => 'Session Auth'],
                ], [
                    'label'       => 'Grouped Select Dropdown',
                    'placeholder' => 'Choose a category...',
                ]) ?>

                <div class="space-y-4 pt-2">
                    <?= ui_toggle('dark_mode_toggle', 'Enable Dark Material Mode', true, [
                        'helper' => 'Smooth iOS-style switch transition',
                    ]) ?>
                    <?= ui_toggle('push_notif', 'Enable Real-time Push Notifications', false, [
                        'helper' => 'Receive browser notifications on events',
                    ]) ?>
                </div>
            </div>
        </div>

        <!-- 6. Metrics & Stat Cards -->
        <div class="rounded-card bg-white border border-slate-200/80 p-6 space-y-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h3 class="text-base font-semibold text-slate-900 tracking-tight">Metrics & Dashboard Stat Cards</h3>
                    <p class="text-xs text-slate-500">Apple Health-inspired metrics with trend pills and structured icon boxes.</p>
                </div>
                <code class="text-[11px] font-mono bg-slate-100 text-slate-600 px-2 py-1 rounded">ui_stat_card()</code>
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
                    'subtitle'    => 'WhatsApp & Direct checkout',
                ]) ?>

                <?= ui_stat_card('Memory Footprint', '1.8 MB', [
                    'icon'        => 'cpu',
                    'iconVariant' => 'slate',
                    'trend'       => 'Optimal',
                    'trendType'   => 'neutral',
                    'subtitle'    => 'Zero heavy framework overhead',
                ]) ?>
            </div>
        </div>

        <!-- 7. Avatars, Stacks & Modals Trigger -->
        <div class="rounded-card bg-white border border-slate-200/80 p-6 space-y-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h3 class="text-base font-semibold text-slate-900 tracking-tight">Avatars, Stacks & Modal Dialogs</h3>
                    <p class="text-xs text-slate-500">User avatars with presence indicators, avatar groups, and sheet dialogs.</p>
                </div>
                <code class="text-[11px] font-mono bg-slate-100 text-slate-600 px-2 py-1 rounded">ui_avatar() / ui_modal()</code>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-6">
                <!-- Avatar presence -->
                <div class="space-y-2">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Avatars with Presence</span>
                    <div class="flex items-center gap-3">
                        <?= ui_avatar('Alexander', ['size' => 'lg', 'status' => 'online']) ?>
                        <?= ui_avatar('Sarah Chen', ['size' => 'lg', 'status' => 'busy']) ?>
                        <?= ui_avatar('Marcus Brody', ['size' => 'lg', 'status' => 'away']) ?>
                        <?= ui_avatar('Elena Rostova', ['size' => 'lg', 'status' => 'offline']) ?>
                    </div>
                </div>

                <!-- Avatar Group Stack -->
                <div class="space-y-2">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Avatar Group Stack</span>
                    <div>
                        <?= ui_avatar_group(['Alex', 'Sarah', 'Marcus', 'Elena', 'David', 'Rachel'], ['size' => 'md', 'max' => 4]) ?>
                    </div>
                </div>

                <!-- Modal Trigger -->
                <div class="space-y-2">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Apple Sheet Modal</span>
                    <div>
                        <button 
                            type="button" 
                            @click="showSampleModal = true" 
                            class="px-4 py-2.5 rounded-btn bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold transition apple-tap flex items-center gap-2">
                            <?= ui_icon('browsers', 'text-sm') ?>
                            <span>Launch Live Modal Sheet</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </section>

    <!-- Live Demo Modal Instance -->
    <?= ui_modal('showSampleModal', 'Apple Design Modal Dialog', '
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
