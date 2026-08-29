<?php
/**
 * Admin Panel Header & Sidebar Layout
 */
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../helpers/format.php';
require_once __DIR__ . '/../../helpers/auth.php';

require_admin_auth();

$admin = get_current_admin();
$settings = get_settings();
$flashSuccess = get_flash('success');
$flashError = get_flash('error');

// Get total pending orders count for badge
$pendingOrdersCount = 0;
$db = getDB();
if ($db) {
    try {
        $countStmt = $db->query("SELECT COUNT(*) FROM orders WHERE status = 'pending'");
        $pendingOrdersCount = (int)$countStmt->fetchColumn();
    } catch (Exception $e) {}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= sanitize($page_title ?? 'Dashboard') ?> - Admin Panel</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <!-- Design System Theme & Token Engine (Dynamic CSS Variables) -->
    <?php render_theme_head(); ?>

    <!-- Vite Assets (Tailwind CSS, Alpine.js, Phosphor Icons) -->
    <?= vite('resources/js/main.js') ?>
</head>
<body class="bg-slate-100/90 text-slate-800 font-sans antialiased flex min-h-screen" x-data="{ sidebarOpen: false }">

    <!-- Mobile Sidebar Backdrop -->
    <div 
        x-cloak 
        x-show="sidebarOpen" 
        @click="sidebarOpen = false" 
        class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-xs lg:hidden">
    </div>

    <!-- Sidebar Navigation (Crisp Border, Zero Shadow) -->
    <aside 
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-slate-300 flex flex-col justify-between transition-transform duration-300 ease-in-out border-r border-slate-800">
        
        <div>
            <!-- Store Brand -->
            <div class="h-20 flex items-center justify-between px-6 border-b border-slate-800">
                <a href="<?= base_url('admin/index.php') ?>" class="flex items-center gap-3 apple-tap">
                    <div class="w-10 h-10 rounded-btn bg-brand-600 border border-brand-500/30 text-white flex items-center justify-center font-semibold">
                        <i class="ph ph-shield-check text-xl"></i>
                    </div>
                    <div>
                        <span class="font-semibold text-sm text-white tracking-tight block">Admin Panel</span>
                        <span class="text-[11px] text-slate-500 truncate block max-w-[120px]"><?= sanitize($settings['store_name']) ?></span>
                    </div>
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white p-1 rounded-btn apple-tap">
                    <i class="ph ph-x text-lg"></i>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="p-4 space-y-1.5 text-xs font-semibold">
                <a 
                    href="<?= base_url('admin/index.php') ?>" 
                    class="flex items-center gap-3 px-3.5 py-3 rounded-btn transition apple-tap <?= !isset($active_menu) || $active_menu === 'dashboard' ? 'bg-brand-600 text-white font-semibold border border-brand-500/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/80 border border-transparent' ?>">
                    <i class="ph ph-squares-four text-base"></i>
                    <span>Main Dashboard</span>
                </a>

                <a 
                    href="<?= base_url('admin/products.php') ?>" 
                    class="flex items-center gap-3 px-3.5 py-3 rounded-btn transition apple-tap <?= isset($active_menu) && $active_menu === 'products' ? 'bg-brand-600 text-white font-semibold border border-brand-500/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/80 border border-transparent' ?>">
                    <i class="ph ph-package text-base"></i>
                    <span>Product Catalog</span>
                </a>

                <a 
                    href="<?= base_url('admin/categories.php') ?>" 
                    class="flex items-center gap-3 px-3.5 py-3 rounded-btn transition apple-tap <?= isset($active_menu) && $active_menu === 'categories' ? 'bg-brand-600 text-white font-semibold border border-brand-500/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/80 border border-transparent' ?>">
                    <i class="ph ph-tag text-base"></i>
                    <span>Product Categories</span>
                </a>

                <a 
                    href="<?= base_url('admin/orders.php') ?>" 
                    class="flex items-center justify-between px-3.5 py-3 rounded-btn transition apple-tap <?= isset($active_menu) && $active_menu === 'orders' ? 'bg-brand-600 text-white font-semibold border border-brand-500/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/80 border border-transparent' ?>">
                    <div class="flex items-center gap-3">
                        <i class="ph ph-shopping-cart text-base"></i>
                        <span>WhatsApp Orders</span>
                    </div>
                    <?php if ($pendingOrdersCount > 0): ?>
                        <span class="px-2 py-0.5 rounded-full bg-rose-500 text-white text-[10px] font-semibold">
                            <?= $pendingOrdersCount ?>
                        </span>
                    <?php endif; ?>
                </a>

                <a 
                    href="<?= base_url('admin/settings.php') ?>" 
                    class="flex items-center gap-3 px-3.5 py-3 rounded-btn transition apple-tap <?= isset($active_menu) && $active_menu === 'settings' ? 'bg-brand-600 text-white font-semibold border border-brand-500/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/80 border border-transparent' ?>">
                    <i class="ph ph-gear text-base"></i>
                    <span>Store &amp; WA Settings</span>
                </a>
            </nav>
        </div>

        <!-- Bottom User & Storefront Link -->
        <div class="p-4 border-t border-slate-800 space-y-2">
            <a 
                href="<?= base_url('demo.php') ?>" 
                target="_blank" 
                class="flex items-center gap-2 px-3 py-2 rounded-btn text-slate-400 hover:text-brand-300 hover:bg-slate-800 text-xs transition apple-tap">
                <i class="ph ph-arrow-square-out text-base"></i>
                <span>View Live Storefront</span>
            </a>

            <div class="pt-2 border-t border-slate-800/60 flex items-center justify-between px-1">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-btn bg-slate-800 border border-slate-700/80 flex items-center justify-center font-semibold text-white text-xs">
                        <?= strtoupper(substr($admin['username'] ?? 'A', 0, 1)) ?>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-semibold text-white truncate max-w-[90px]"><?= sanitize($admin['username'] ?? 'Admin') ?></p>
                        <span class="text-[10px] text-brand-400">Online</span>
                    </div>
                </div>

                <a href="<?= base_url('admin/logout.php') ?>" onclick="return confirm('Are you sure you want to log out?')" class="text-slate-400 hover:text-rose-400 p-1.5 rounded-btn hover:bg-slate-800 transition apple-tap" title="Logout">
                    <i class="ph ph-sign-out text-base"></i>
                </a>
            </div>
        </div>

    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 lg:pl-64 flex flex-col min-w-0">
        
        <!-- Top Header Bar (Translucent Apple Material, Zero Shadow) -->
        <header class="h-20 bg-white/90 backdrop-blur-xl border-b border-slate-200/80 px-4 sm:px-8 flex items-center justify-between sticky top-0 z-30">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-btn text-slate-600 hover:bg-slate-100 border border-slate-200/80 apple-tap">
                    <i class="ph ph-list text-2xl"></i>
                </button>
                <div>
                    <h1 class="text-base sm:text-lg font-extrabold text-slate-900 tracking-tight"><?= sanitize($page_title ?? 'Dashboard') ?></h1>
                    <p class="text-xs text-slate-400 hidden sm:block">Manage your product catalog and customer orders easily.</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <?= ui_button('Add Product', [
                    'variant' => 'primary',
                    'size'    => 'sm',
                    'href'    => base_url('admin/product-form.php'),
                    'icon'    => 'plus',
                ]) ?>
            </div>
        </header>

        <!-- Flash Messages Alerts -->
        <div class="px-4 sm:px-8 pt-6">
            <?php if (!empty($flashSuccess)): ?>
                <?= ui_alert(sanitize($flashSuccess), 'success', ['class' => 'mb-6', 'dismissible' => true]) ?>
            <?php endif; ?>

            <?php if (!empty($flashError)): ?>
                <?= ui_alert(sanitize($flashError), 'danger', ['class' => 'mb-6', 'dismissible' => true]) ?>
            <?php endif; ?>
        </div>

        <main class="p-4 sm:p-8 flex-1">
