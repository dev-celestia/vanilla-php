<?php
/**
 * Main Storefront Header
 */
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../helpers/format.php';
$settings = get_settings();

// Check if currently on the demo showcase or its checkout/product/contact pages
$is_demo_page = (isset($active_nav) && in_array($active_nav, ['demo', 'cart', 'checkout', 'contact'])) || in_array(basename($_SERVER['PHP_SELF'] ?? ''), ['demo.php', 'product.php', 'cart.php', 'checkout.php', 'order-success.php', 'contact.php']);
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= sanitize($page_title ?? $settings['store_name'] . ' - ' . $settings['store_slogan']) ?></title>
    <meta name="description" content="<?= sanitize($settings['store_description']) ?>">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <!-- Design System Theme & Token Engine (Dynamic CSS Variables) -->
    <?php render_theme_head(); ?>

    <!-- Vite Assets (Tailwind CSS, Alpine.js, Phosphor Icons, Cart Store) -->
    <?= vite('resources/js/main.js') ?>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased flex flex-col min-h-screen" x-data="{ mobileMenuOpen: false }">

    <!-- Top Slim Announcement Bar -->
    <div class="bg-slate-950 text-slate-400 text-[11px] py-1.5 px-4 border-b border-slate-800/80">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-2">
            <?php if ($is_demo_page): ?>
                <div class="flex items-center gap-2">
                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="font-normal text-slate-300">Storefront Demo &bull; Belanja Cepat Langsung via WhatsApp</span>
                </div>
                <div class="flex items-center gap-3 text-slate-400">
                    <a href="https://github.com/dev-celestia/vanilla-php" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors flex items-center gap-1">
                        <i class="ph ph-github-logo text-[11px]"></i>
                        <span>GitHub</span>
                    </a>
                    <span class="text-slate-700">•</span>
                    <a href="<?= base_url('scaffold.php') ?>" class="text-emerald-400 hover:text-emerald-300 font-semibold transition-colors flex items-center gap-1">
                        <i class="ph-bold ph-lightning text-[11px]"></i>
                        <span>Scaffold Proyek Toko Ini</span>
                    </a>
                    <span class="text-slate-700">•</span>
                    <a href="<?= base_url() ?>" class="hover:text-white transition-colors flex items-center gap-1">
                        <i class="ph ph-arrow-left text-[11px]"></i>
                        <span>Kembali ke Framework UI</span>
                    </a>
                    <span class="text-slate-700">•</span>
                    <a href="<?= base_url('admin/login.php') ?>" class="hover:text-white transition-colors flex items-center gap-1 text-slate-400">
                        <i class="ph ph-shield-check text-[11px]"></i>
                        <span>Admin</span>
                    </a>
                </div>
            <?php else: ?>
                <div class="flex items-center gap-2">
                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-brand-400 animate-pulse"></span>
                    <span class="font-normal text-slate-300">Vanilla PHP UI Component Library & Starter Stack</span>
                </div>
                <div class="flex items-center gap-3 text-slate-400">
                    <a href="https://github.com/dev-celestia/vanilla-php" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors flex items-center gap-1">
                        <i class="ph ph-github-logo text-[11px]"></i>
                        <span>GitHub</span>
                    </a>
                    <span class="text-slate-700">•</span>
                    <a href="<?= base_url('scaffold.php') ?>" class="text-emerald-400 hover:text-emerald-300 transition-colors flex items-center gap-1 font-semibold">
                        <i class="ph-bold ph-lightning text-[11px]"></i>
                        <span>App Scaffolder</span>
                    </a>
                    <span class="text-slate-700">•</span>
                    <a href="<?= base_url('design-system.php') ?>" class="hover:text-brand-300 transition-colors flex items-center gap-1">
                        <i class="ph ph-palette text-[11px] text-brand-400"></i>
                        <span>Live Token Explorer</span>
                    </a>
                    <span class="text-slate-700">•</span>
                    <a href="<?= base_url('admin/login.php') ?>" class="hover:text-white transition-colors flex items-center gap-1 text-slate-400">
                        <i class="ph ph-shield-check text-[11px]"></i>
                        <span>Admin</span>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Main Navigation Bar (Distinct Navbar for Demo Store vs Framework Landing) -->
    <header class="sticky top-0 z-40 bg-white/80 backdrop-blur-xl border-b border-slate-200/70 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-14 sm:h-16">
                
                <?php if ($is_demo_page): ?>
                    <!-- ============================================== -->
                    <!-- DEMO STOREFRONT NAVBAR                         -->
                    <!-- ============================================== -->
                    <!-- Store Brand Logo -->
                    <a href="<?= base_url('demo.php') ?>" class="flex items-center gap-2.5 group apple-tap">
                        <div class="w-8 h-8 rounded-btn bg-brand-600 flex items-center justify-center text-white border border-brand-500/20 group-hover:scale-105 transition-transform duration-150">
                            <i class="ph-bold ph-storefront text-base"></i>
                        </div>
                        <div>
                            <span class="font-semibold text-sm tracking-tight text-slate-900 block leading-none flex items-center gap-1">
                                <?= sanitize($settings['store_name'] ?? 'KatalogStore') ?>
                            </span>
                            <span class="text-[10px] text-slate-400 font-normal hidden lg:block leading-none mt-0.5">
                                <?= sanitize($settings['store_slogan'] ?? 'Official Online Store') ?>
                            </span>
                        </div>
                    </a>

                    <!-- Storefront Desktop Nav Links -->
                    <nav class="hidden md:flex items-center space-x-1">
                        <a href="<?= base_url('demo.php') ?>" class="px-3 py-1.5 text-[13px] font-medium rounded-btn transition-colors apple-tap <?= !isset($active_nav) || $active_nav === 'demo' ? 'text-brand-700 bg-brand-50/80 border border-brand-200/60 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 border border-transparent' ?>">
                            <i class="ph ph-storefront mr-1"></i> Katalog
                        </a>
                        <a href="<?= base_url('cart.php') ?>" class="px-3 py-1.5 text-[13px] font-medium rounded-btn transition-colors apple-tap <?= isset($active_nav) && $active_nav === 'cart' ? 'text-brand-700 bg-brand-50/80 border border-brand-200/60 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 border border-transparent' ?>">
                            <i class="ph ph-shopping-bag mr-1"></i> Keranjang
                        </a>
                        <a href="<?= base_url('about.php') ?>" class="px-3 py-1.5 text-[13px] font-medium rounded-btn transition-colors apple-tap <?= isset($active_nav) && $active_nav === 'about' ? 'text-brand-700 bg-brand-50/80 border border-brand-200/60 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 border border-transparent' ?>">
                            Tentang Kami
                        </a>
                        <a href="<?= base_url('contact.php') ?>" class="px-3 py-1.5 text-[13px] font-medium rounded-btn transition-colors apple-tap <?= isset($active_nav) && $active_nav === 'contact' ? 'text-brand-700 bg-brand-50/80 border border-brand-200/60 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 border border-transparent' ?>">
                            Hubungi Kami
                        </a>
                    </nav>

                    <!-- Storefront Actions -->
                    <div class="flex items-center gap-2">
                        <!-- Search input -->
                        <form action="<?= base_url('demo.php') ?>" method="GET" class="hidden xl:flex items-center relative">
                            <input type="text" name="q" value="<?= sanitize($_GET['q'] ?? '') ?>" placeholder="Cari barang..." class="w-32 focus:w-48 transition-all duration-200 pl-7 pr-2.5 py-1 text-xs rounded-input bg-slate-100/80 border border-slate-200/70 focus:border-brand-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                            <i class="ph ph-magnifying-glass text-slate-400 absolute left-2 top-1/2 -translate-y-1/2 pointer-events-none text-xs"></i>
                        </form>

                        <!-- Cart Drawer Trigger Button -->
                        <button 
                            type="button" 
                            @click="$store.cart.isOpen = true" 
                            class="relative px-2.5 py-1.5 rounded-btn bg-slate-100 hover:bg-brand-50 hover:text-brand-600 text-slate-700 border border-slate-200/80 transition-colors flex items-center gap-1.5 group apple-tap text-xs font-semibold"
                            title="Buka Keranjang Belanja">
                            <i class="ph ph-shopping-bag text-base group-hover:scale-105 transition-transform"></i>
                            <span class="hidden sm:inline" x-text="$store.cart.formatRupiah($store.cart.subtotal)">Rp 0</span>
                            
                            <!-- Badge Count -->
                            <span 
                                x-show="$store.cart.count > 0" 
                                x-cloak
                                x-text="$store.cart.count" 
                                class="w-4 h-4 rounded-full bg-brand-600 text-white text-[9px] font-semibold flex items-center justify-center border border-white">
                            </span>
                        </button>

                        <!-- Scaffold This Store Shortcut -->
                        <a href="<?= base_url('scaffold.php') ?>" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-btn text-xs font-semibold text-emerald-700 hover:text-emerald-800 bg-emerald-50 hover:bg-emerald-100/80 border border-emerald-200/80 transition apple-tap" title="Scaffold / Copy Storefront to New Directory">
                            <i class="ph-bold ph-lightning text-xs text-emerald-600"></i>
                            <span>Scaffold</span>
                        </a>

                        <!-- GitHub Link -->
                        <a href="https://github.com/dev-celestia/vanilla-php" target="_blank" rel="noopener noreferrer" class="hidden sm:inline-flex items-center justify-center w-8 h-8 rounded-btn text-slate-600 hover:text-slate-900 bg-slate-100/80 hover:bg-slate-200/70 border border-slate-200/80 transition apple-tap" title="GitHub Repository">
                            <i class="ph-bold ph-github-logo text-base"></i>
                        </a>

                        <!-- Mobile Menu Button -->
                        <button 
                            @click="mobileMenuOpen = !mobileMenuOpen" 
                            class="md:hidden p-2 rounded-btn text-slate-600 hover:bg-slate-100 border border-slate-200/80 apple-tap focus:outline-none"
                            aria-label="Toggle Navigation">
                            <i class="ph text-xl" :class="mobileMenuOpen ? 'ph-x' : 'ph-list'"></i>
                        </button>
                    </div>

                <?php else: ?>
                    <!-- ============================================== -->
                    <!-- FRAMEWORK & DESIGN SYSTEM NAVBAR               -->
                    <!-- ============================================== -->
                    <!-- Framework Logo -->
                    <a href="<?= base_url() ?>" class="flex items-center gap-2.5 group apple-tap">
                        <div class="w-8 h-8 rounded-btn bg-brand-600 flex items-center justify-center text-white border border-brand-500/20 group-hover:scale-105 transition-transform duration-150">
                            <i class="ph ph-code-simple text-base"></i>
                        </div>
                        <div>
                            <span class="font-semibold text-sm tracking-tight text-slate-900 block leading-none flex items-center gap-1">
                                VanillaPHP <span class="px-1.5 py-0.5 rounded bg-brand-50 text-brand-700 text-[10px] font-semibold border border-brand-200/80">UI</span>
                            </span>
                            <span class="text-[10px] text-slate-400 font-normal hidden lg:block leading-none mt-0.5">
                                Design System
                            </span>
                        </div>
                    </a>

                    <!-- Framework Desktop Nav Links -->
                    <nav class="hidden md:flex items-center space-x-1">
                        <a href="<?= base_url() ?>" class="px-3 py-1.5 text-[13px] font-medium rounded-btn transition-colors apple-tap <?= !isset($active_nav) || $active_nav === 'home' ? 'text-brand-700 bg-brand-50/80 border border-brand-200/60 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 border border-transparent' ?>">
                            Overview
                        </a>
                        <a href="<?= base_url('design-system.php') ?>" class="px-3 py-1.5 text-[13px] font-medium rounded-btn transition-colors apple-tap <?= isset($active_nav) && $active_nav === 'design_system' ? 'text-brand-700 bg-brand-50/80 border border-brand-200/60 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 border border-transparent' ?>">
                            Design Tokens
                        </a>
                        <a href="<?= base_url('components.php') ?>" class="px-3 py-1.5 text-[13px] font-medium rounded-btn transition-colors apple-tap <?= isset($active_nav) && $active_nav === 'components' ? 'text-brand-700 bg-brand-50/80 border border-brand-200/60 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 border border-transparent' ?>">
                            UI Components
                        </a>
                        <a href="<?= base_url('about.php') ?>" class="px-3 py-1.5 text-[13px] font-medium rounded-btn transition-colors apple-tap <?= isset($active_nav) && $active_nav === 'about' ? 'text-brand-700 bg-brand-50/80 border border-brand-200/60 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 border border-transparent' ?>">
                            Architecture
                        </a>
                        <a href="<?= base_url('demo.php') ?>" class="px-3 py-1.5 text-[13px] font-medium rounded-btn transition-colors apple-tap <?= isset($active_nav) && $active_nav === 'demo' ? 'text-brand-700 bg-brand-50/80 border border-brand-200/60 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 border border-transparent' ?>">
                            <i class="ph ph-shopping-bag mr-1 text-brand-600"></i> Live Demo
                        </a>
                        <a href="<?= base_url('scaffold.php') ?>" class="px-3 py-1.5 text-[13px] font-medium rounded-btn transition-colors apple-tap <?= isset($active_nav) && $active_nav === 'scaffold' ? 'text-brand-700 bg-brand-50/80 border border-brand-200/60 font-semibold' : 'text-emerald-700 hover:text-emerald-900 hover:bg-emerald-50/80 border border-transparent font-medium' ?>">
                            <i class="ph-bold ph-lightning mr-1 text-emerald-600"></i> Scaffolder
                        </a>
                    </nav>

                    <!-- Framework Actions -->
                    <div class="flex items-center gap-2">
                        <a href="<?= base_url('demo.php') ?>" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-btn text-xs font-semibold text-slate-700 hover:text-slate-900 bg-slate-100/80 hover:bg-slate-200/70 border border-slate-200/80 transition apple-tap">
                            <i class="ph ph-shopping-bag text-xs text-brand-600"></i>
                            <span>Live Demo</span>
                        </a>

                        <a href="<?= base_url('scaffold.php') ?>" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-btn text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-500 border border-emerald-500/30 transition apple-tap">
                            <i class="ph-bold ph-lightning text-xs"></i>
                            <span>Scaffold App</span>
                        </a>

                        <!-- GitHub Link -->
                        <a href="https://github.com/dev-celestia/vanilla-php" target="_blank" rel="noopener noreferrer" class="hidden sm:inline-flex items-center justify-center w-8 h-8 rounded-btn text-slate-600 hover:text-slate-900 bg-slate-100/80 hover:bg-slate-200/70 border border-slate-200/80 transition apple-tap" title="GitHub Repository">
                            <i class="ph-bold ph-github-logo text-base"></i>
                        </a>

                        <!-- Mobile Menu Button -->
                        <button 
                            @click="mobileMenuOpen = !mobileMenuOpen" 
                            class="md:hidden p-2 rounded-btn text-slate-600 hover:bg-slate-100 border border-slate-200/80 apple-tap focus:outline-none"
                            aria-label="Toggle Navigation">
                            <i class="ph text-xl" :class="mobileMenuOpen ? 'ph-x' : 'ph-list'"></i>
                        </button>
                    </div>
                <?php endif; ?>

            </div>
        </div>

        <!-- Mobile Menu Drawer -->
        <div 
            x-show="mobileMenuOpen" 
            x-cloak
            @click.away="mobileMenuOpen = false" 
            class="md:hidden border-t border-slate-200/80 bg-white/95 backdrop-blur-xl px-4 pt-3 pb-5 space-y-2.5">
            
            <?php if ($is_demo_page): ?>
                <div class="space-y-1 text-[13px]">
                    <a href="<?= base_url('demo.php') ?>" class="block px-3.5 py-2 rounded-btn font-medium <?= !isset($active_nav) || $active_nav === 'demo' ? 'bg-brand-50 text-brand-700 font-semibold border border-brand-200/80' : 'text-slate-700 hover:bg-slate-50 border border-transparent' ?>">
                        <i class="ph ph-storefront mr-1"></i> Katalog Produk
                    </a>
                    <a href="<?= base_url('cart.php') ?>" class="block px-3.5 py-2 rounded-btn font-medium <?= isset($active_nav) && $active_nav === 'cart' ? 'bg-brand-50 text-brand-700 font-semibold border border-brand-200/80' : 'text-slate-700 hover:bg-slate-50 border border-transparent' ?>">
                        <i class="ph ph-shopping-bag mr-1"></i> Keranjang Belanja
                    </a>
                    <a href="<?= base_url('about.php') ?>" class="block px-3.5 py-2 rounded-btn font-medium <?= isset($active_nav) && $active_nav === 'about' ? 'bg-brand-50 text-brand-700 font-semibold border border-brand-200/80' : 'text-slate-700 hover:bg-slate-50 border border-transparent' ?>">
                        Tentang Kami
                    </a>
                    <a href="<?= base_url('contact.php') ?>" class="block px-3.5 py-2 rounded-btn font-medium <?= isset($active_nav) && $active_nav === 'contact' ? 'bg-brand-50 text-brand-700 font-semibold border border-brand-200/80' : 'text-slate-700 hover:bg-slate-50 border border-transparent' ?>">
                        Hubungi Kami
                    </a>
                    <a href="<?= base_url('scaffold.php') ?>" class="block px-3.5 py-2 rounded-btn font-medium text-emerald-700 bg-emerald-50/80 border border-emerald-200/80">
                        <i class="ph-bold ph-lightning mr-1"></i> Scaffold Toko Ini
                    </a>
                    <a href="https://github.com/dev-celestia/vanilla-php" target="_blank" rel="noopener noreferrer" class="block px-3.5 py-2 rounded-btn font-medium text-slate-700 hover:bg-slate-50 border border-transparent">
                        <i class="ph-bold ph-github-logo mr-1"></i> GitHub Repository
                    </a>
                    <a href="<?= base_url() ?>" class="block px-3.5 py-2 rounded-btn font-medium text-slate-500 hover:bg-slate-50 border border-transparent">
                        <i class="ph ph-arrow-left mr-1"></i> Kembali ke Framework
                    </a>
                </div>

                <div class="pt-2 border-t border-slate-100">
                    <button 
                        type="button" 
                        @click="$store.cart.isOpen = true; mobileMenuOpen = false" 
                        class="w-full flex items-center justify-between px-3.5 py-2 rounded-btn bg-slate-100 text-slate-800 text-xs font-semibold apple-tap">
                        <span class="flex items-center gap-2">
                            <i class="ph ph-shopping-bag text-base text-brand-600"></i>
                            <span>Buka Keranjang Belanja</span>
                        </span>
                        <span class="px-2 py-0.5 rounded-full bg-brand-600 text-white text-[10px]" x-text="$store.cart.count">0</span>
                    </button>
                </div>
            <?php else: ?>
                <div class="space-y-1 text-[13px]">
                    <a href="<?= base_url() ?>" class="block px-3.5 py-2 rounded-btn font-medium <?= !isset($active_nav) || $active_nav === 'home' ? 'bg-brand-50 text-brand-700 font-semibold border border-brand-200/80' : 'text-slate-700 hover:bg-slate-50 border border-transparent' ?>">
                        Overview
                    </a>
                    <a href="<?= base_url('design-system.php') ?>" class="block px-3.5 py-2 rounded-btn font-medium <?= isset($active_nav) && $active_nav === 'design_system' ? 'bg-brand-50 text-brand-700 font-semibold border border-brand-200/80' : 'text-slate-700 hover:bg-slate-50 border border-transparent' ?>">
                        Design Tokens
                    </a>
                    <a href="<?= base_url('components.php') ?>" class="block px-3.5 py-2 rounded-btn font-medium <?= isset($active_nav) && $active_nav === 'components' ? 'bg-brand-50 text-brand-700 font-semibold border border-brand-200/80' : 'text-slate-700 hover:bg-slate-50 border border-transparent' ?>">
                        UI Components
                    </a>
                    <a href="<?= base_url('about.php') ?>" class="block px-3.5 py-2 rounded-btn font-medium <?= isset($active_nav) && $active_nav === 'about' ? 'bg-brand-50 text-brand-700 font-semibold border border-brand-200/80' : 'text-slate-700 hover:bg-slate-50 border border-transparent' ?>">
                        Architecture & Philosophy
                    </a>
                    <a href="<?= base_url('demo.php') ?>" class="block px-3.5 py-2 rounded-btn font-medium text-slate-700 hover:bg-slate-50 border border-transparent">
                        <i class="ph ph-shopping-bag mr-1 text-brand-600"></i> Showcase Demo
                    </a>
                    <a href="<?= base_url('scaffold.php') ?>" class="block px-3.5 py-2 rounded-btn font-medium text-emerald-700 bg-emerald-50/80 border border-emerald-200/80">
                        <i class="ph-bold ph-lightning mr-1 text-emerald-600"></i> App Scaffolder
                    </a>
                    <a href="https://github.com/dev-celestia/vanilla-php" target="_blank" rel="noopener noreferrer" class="block px-3.5 py-2 rounded-btn font-medium text-slate-700 hover:bg-slate-50 border border-transparent">
                        <i class="ph-bold ph-github-logo mr-1"></i> GitHub Repository
                    </a>
                </div>

                <div class="pt-2 border-t border-slate-100 flex gap-2">
                    <a href="<?= base_url('demo.php') ?>" class="flex-1 py-2 rounded-btn bg-slate-100 text-slate-800 text-xs font-semibold text-center apple-tap">
                        Live Demo
                    </a>
                    <a href="<?= base_url('scaffold.php') ?>" class="flex-1 py-2 rounded-btn bg-emerald-600 text-white text-xs font-semibold text-center apple-tap">
                        Scaffold
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </header>

    <!-- Slide-over Cart Drawer (Rendered for interactions) -->
    <?php require_once __DIR__ . '/cart_drawer.php'; ?>

    <main class="flex-grow">
