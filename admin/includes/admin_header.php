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
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= sanitize($page_title ?? 'Dashboard') ?> - Admin Panel</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind Play CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Design System Theme & Token Engine -->
    <?php render_theme_head(); ?>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
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
                    <div class="w-10 h-10 rounded-btn bg-brand-600 border border-brand-500/30 text-white flex items-center justify-center font-bold">
                        <i data-lucide="shield-check" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <span class="font-extrabold text-sm text-white tracking-tight block">Panel Admin</span>
                        <span class="text-[11px] text-slate-500 truncate block max-w-[120px]"><?= sanitize($settings['store_name']) ?></span>
                    </div>
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white p-1 rounded-btn apple-tap">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="p-4 space-y-1.5 text-xs font-semibold">
                <a 
                    href="<?= base_url('admin/index.php') ?>" 
                    class="flex items-center gap-3 px-3.5 py-3 rounded-btn transition apple-tap <?= !isset($active_menu) || $active_menu === 'dashboard' ? 'bg-brand-600 text-white font-bold border border-brand-500/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/80 border border-transparent' ?>">
                    <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                    <span>Dashboard Utama</span>
                </a>

                <a 
                    href="<?= base_url('admin/products.php') ?>" 
                    class="flex items-center gap-3 px-3.5 py-3 rounded-btn transition apple-tap <?= isset($active_menu) && $active_menu === 'products' ? 'bg-brand-600 text-white font-bold border border-brand-500/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/80 border border-transparent' ?>">
                    <i data-lucide="package" class="w-4 h-4"></i>
                    <span>Katalog Produk</span>
                </a>

                <a 
                    href="<?= base_url('admin/categories.php') ?>" 
                    class="flex items-center gap-3 px-3.5 py-3 rounded-btn transition apple-tap <?= isset($active_menu) && $active_menu === 'categories' ? 'bg-brand-600 text-white font-bold border border-brand-500/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/80 border border-transparent' ?>">
                    <i data-lucide="tags" class="w-4 h-4"></i>
                    <span>Kategori Produk</span>
                </a>

                <a 
                    href="<?= base_url('admin/orders.php') ?>" 
                    class="flex items-center justify-between px-3.5 py-3 rounded-btn transition apple-tap <?= isset($active_menu) && $active_menu === 'orders' ? 'bg-brand-600 text-white font-bold border border-brand-500/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/80 border border-transparent' ?>">
                    <div class="flex items-center gap-3">
                        <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                        <span>Pesanan WhatsApp</span>
                    </div>
                    <?php if ($pendingOrdersCount > 0): ?>
                        <span class="px-2 py-0.5 rounded-full bg-rose-500 text-white text-[10px] font-extrabold">
                            <?= $pendingOrdersCount ?>
                        </span>
                    <?php endif; ?>
                </a>

                <a 
                    href="<?= base_url('admin/settings.php') ?>" 
                    class="flex items-center gap-3 px-3.5 py-3 rounded-btn transition apple-tap <?= isset($active_menu) && $active_menu === 'settings' ? 'bg-brand-600 text-white font-bold border border-brand-500/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/80 border border-transparent' ?>">
                    <i data-lucide="settings" class="w-4 h-4"></i>
                    <span>Pengaturan Toko & WA</span>
                </a>

                <div class="pt-3 pb-1 px-3">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Design System</span>
                </div>

                <a 
                    href="<?= base_url('admin/design-system.php') ?>" 
                    class="flex items-center gap-3 px-3.5 py-3 rounded-btn transition apple-tap <?= isset($active_menu) && $active_menu === 'design_system' ? 'bg-brand-600 text-white font-bold border border-brand-500/20' : 'text-brand-400 bg-brand-950/40 hover:text-white hover:bg-slate-800/80 border border-brand-800/40' ?>">
                    <i data-lucide="palette" class="w-4 h-4"></i>
                    <span>Showcase Primitif UI</span>
                </a>
            </nav>
        </div>

        <!-- Bottom User & Storefront Link -->
        <div class="p-4 border-t border-slate-800 space-y-2">
            <a 
                href="<?= base_url() ?>" 
                target="_blank" 
                class="flex items-center gap-2 px-3 py-2 rounded-btn text-slate-400 hover:text-brand-300 hover:bg-slate-800 text-xs transition apple-tap">
                <i data-lucide="external-link" class="w-4 h-4"></i>
                <span>Lihat Website Toko</span>
            </a>

            <div class="pt-2 border-t border-slate-800/60 flex items-center justify-between px-1">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-btn bg-slate-800 border border-slate-700/80 flex items-center justify-center font-bold text-white text-xs">
                        <?= strtoupper(substr($admin['username'] ?? 'A', 0, 1)) ?>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-white truncate max-w-[90px]"><?= sanitize($admin['username'] ?? 'Admin') ?></p>
                        <span class="text-[10px] text-brand-400">Online</span>
                    </div>
                </div>

                <a href="<?= base_url('admin/logout.php') ?>" onclick="return confirm('Apakah Anda yakin ingin logout?')" class="text-slate-400 hover:text-rose-400 p-1.5 rounded-btn hover:bg-slate-800 transition apple-tap" title="Keluar">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
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
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
                <div>
                    <h1 class="text-base sm:text-lg font-extrabold text-slate-900 tracking-tight"><?= sanitize($page_title ?? 'Dashboard') ?></h1>
                    <p class="text-xs text-slate-400 hidden sm:block">Kelola katalog dan pemesanan dengan mudah.</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <?= ui_button('Tambah Produk', [
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
