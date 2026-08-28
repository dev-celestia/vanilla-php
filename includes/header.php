<?php
/**
 * Main Storefront Header
 */
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../helpers/format.php';
$settings = get_settings();
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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (Play CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Design System Theme & Token Engine -->
    <?php render_theme_head(); ?>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        [x-cloak] { display: none !important; }
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>

    <!-- Cart Store Init -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('cart', {
                items: JSON.parse(localStorage.getItem('native_shop_cart') || '[]'),
                isOpen: false,
                
                init() {
                    this.save();
                },

                save() {
                    localStorage.setItem('native_shop_cart', JSON.stringify(this.items));
                },

                addItem(product, qty = 1) {
                    const existing = this.items.find(i => i.id === product.id);
                    const quantityToAdd = parseInt(qty) || 1;
                    
                    if (existing) {
                        if (product.stock && (existing.qty + quantityToAdd) > product.stock) {
                            alert('Maaf, jumlah pesanan melebihi stok yang tersedia (' + product.stock + ' unit).');
                            existing.qty = product.stock;
                        } else {
                            existing.qty += quantityToAdd;
                        }
                    } else {
                        if (product.stock && quantityToAdd > product.stock) {
                            alert('Maaf, jumlah melebihi stok yang tersedia.');
                            return;
                        }
                        this.items.push({
                            id: product.id,
                            name: product.name,
                            price: parseFloat(product.price),
                            image: product.image,
                            stock: parseInt(product.stock) || 999,
                            qty: quantityToAdd
                        });
                    }
                    this.save();
                    this.isOpen = true;
                },

                updateQty(id, delta) {
                    const item = this.items.find(i => i.id === id);
                    if (item) {
                        const newQty = item.qty + delta;
                        if (newQty <= 0) {
                            this.removeItem(id);
                        } else if (newQty > item.stock) {
                            alert('Maksimal stok tersedia: ' + item.stock);
                        } else {
                            item.qty = newQty;
                            this.save();
                        }
                    }
                },

                removeItem(id) {
                    this.items = this.items.filter(i => i.id !== id);
                    this.save();
                },

                clearCart() {
                    this.items = [];
                    this.save();
                },

                get count() {
                    return this.items.reduce((sum, item) => sum + item.qty, 0);
                },

                get subtotal() {
                    return this.items.reduce((sum, item) => sum + (item.price * item.qty), 0);
                },

                formatRupiah(amount) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(amount);
                }
            });
        });
    </script>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased flex flex-col min-h-screen" x-data="{ mobileMenuOpen: false }">

    <!-- Top Announcement Bar -->
    <div class="bg-slate-900 text-white text-xs py-2 px-4 border-b border-slate-800">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2">
            <div class="flex items-center gap-2">
                <span class="inline-block w-2 h-2 rounded-full bg-brand-400 animate-pulse"></span>
                <span><?= sanitize($settings['hero_badge'] ?? 'Selamat Datang di Toko Kami!') ?></span>
            </div>
            <div class="flex items-center gap-4 text-slate-300">
                <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $settings['whatsapp_number']) ?>" target="_blank" class="hover:text-brand-300 transition flex items-center gap-1">
                    <i data-lucide="phone-call" class="w-3.5 h-3.5"></i>
                    <span>CS WhatsApp: <?= sanitize($settings['whatsapp_number']) ?></span>
                </a>
                <span class="hidden sm:inline text-slate-600">•</span>
                <a href="<?= base_url('admin/login.php') ?>" class="hover:text-white transition flex items-center gap-1 text-slate-400">
                    <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
                    <span>Admin Panel</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar (Translucent Apple Material, Zero Shadows, Crisp Border) -->
    <header class="sticky top-0 z-40 bg-white/90 backdrop-blur-xl border-b border-slate-200/80 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Logo -->
                <a href="<?= base_url() ?>" class="flex items-center gap-3 group apple-tap">
                    <div class="w-11 h-11 rounded-btn bg-brand-600 flex items-center justify-center text-white border border-brand-500/20 group-hover:scale-105 transition-transform duration-150">
                        <i data-lucide="shopping-bag" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <span class="font-extrabold text-xl tracking-tight text-slate-900 block leading-tight">
                            <?= sanitize($settings['store_name']) ?>
                        </span>
                        <span class="text-[11px] text-slate-500 font-medium hidden sm:block">
                            <?= sanitize($settings['store_slogan']) ?>
                        </span>
                    </div>
                </a>

                <!-- Desktop Nav Links -->
                <nav class="hidden md:flex items-center space-x-1 lg:space-x-2">
                    <a href="<?= base_url() ?>" class="px-4 py-2 text-sm font-semibold rounded-btn transition apple-tap <?= !isset($active_nav) || $active_nav === 'home' ? 'text-brand-700 bg-brand-50 border border-brand-200/80' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 border border-transparent' ?>">
                        Beranda & Katalog
                    </a>
                    <a href="<?= base_url('about.php') ?>" class="px-4 py-2 text-sm font-semibold rounded-btn transition apple-tap <?= isset($active_nav) && $active_nav === 'about' ? 'text-brand-700 bg-brand-50 border border-brand-200/80' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 border border-transparent' ?>">
                        Tentang Kami
                    </a>
                    <a href="<?= base_url('contact.php') ?>" class="px-4 py-2 text-sm font-semibold rounded-btn transition apple-tap <?= isset($active_nav) && $active_nav === 'contact' ? 'text-brand-700 bg-brand-50 border border-brand-200/80' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 border border-transparent' ?>">
                        Kontak & Lokasi
                    </a>
                </nav>

                <!-- Actions (Search, Cart, Direct WA) -->
                <div class="flex items-center gap-2 sm:gap-3">
                    
                    <!-- Search Input (Desktop) -->
                    <form action="<?= base_url() ?>" method="GET" class="hidden lg:flex items-center relative">
                        <input type="text" name="q" value="<?= sanitize($_GET['q'] ?? '') ?>" placeholder="Cari produk..." class="w-44 focus:w-64 transition-all duration-300 pl-9 pr-4 py-2 text-xs rounded-input bg-slate-100 border border-slate-200/60 focus:border-brand-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                        <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 pointer-events-none"></i>
                    </form>

                    <!-- Cart Drawer Trigger Button -->
                    <button 
                        type="button" 
                        @click="$store.cart.isOpen = true"
                        class="relative p-2.5 rounded-btn bg-slate-100 hover:bg-brand-50 hover:text-brand-600 text-slate-700 border border-slate-200/80 transition flex items-center gap-2 group apple-tap"
                        title="Lihat Keranjang">
                        <i data-lucide="shopping-cart" class="w-5 h-5 group-hover:scale-110 transition-transform"></i>
                        <span class="hidden sm:inline text-xs font-bold text-slate-800 group-hover:text-brand-600" x-text="$store.cart.formatRupiah($store.cart.subtotal)">Rp 0</span>
                        
                        <!-- Badge Count -->
                        <span 
                            x-show="$store.cart.count > 0" 
                            x-cloak
                            x-text="$store.cart.count" 
                            class="absolute -top-1.5 -right-1.5 w-5 h-5 rounded-full bg-brand-600 text-white text-[11px] font-bold flex items-center justify-center border border-white">
                        </span>
                    </button>

                    <!-- WhatsApp Quick CTA -->
                    <?= ui_button('Chat Admin', [
                        'variant' => 'primary',
                        'size'    => 'sm',
                        'href'    => 'https://wa.me/' . preg_replace('/[^0-9]/', '', $settings['whatsapp_number']) . '?text=Halo%20Admin%20' . urlencode($settings['store_name']) . ',%20saya%20ingin%20tanya%20produk',
                        'target'  => '_blank',
                        'icon'    => 'message-circle',
                        'class'   => 'hidden sm:inline-flex',
                    ]) ?>

                    <!-- Mobile Menu Button -->
                    <button 
                        @click="mobileMenuOpen = !mobileMenuOpen" 
                        class="md:hidden p-2.5 rounded-btn text-slate-600 hover:bg-slate-100 border border-slate-200/80 apple-tap focus:outline-none">
                        <i :data-lucide="mobileMenuOpen ? 'x' : 'menu'" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Drawer -->
        <div 
            x-show="mobileMenuOpen" 
            x-cloak
            @click.away="mobileMenuOpen = false" 
            class="md:hidden border-t border-slate-200/80 bg-white px-4 pt-3 pb-6 space-y-3">
            
            <form action="<?= base_url() ?>" method="GET" class="relative">
                <input type="text" name="q" value="<?= sanitize($_GET['q'] ?? '') ?>" placeholder="Cari nama produk..." class="w-full pl-10 pr-4 py-2.5 text-sm rounded-input bg-slate-100 border border-slate-200/70 focus:border-brand-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3.5 top-3 pointer-events-none"></i>
            </form>

            <div class="space-y-1">
                <a href="<?= base_url() ?>" class="block px-4 py-2.5 rounded-btn text-sm font-semibold <?= !isset($active_nav) || $active_nav === 'home' ? 'bg-brand-50 text-brand-700 border border-brand-200/80' : 'text-slate-700 hover:bg-slate-50 border border-transparent' ?>">
                    🏠 Beranda & Katalog
                </a>
                <a href="<?= base_url('about.php') ?>" class="block px-4 py-2.5 rounded-btn text-sm font-semibold <?= isset($active_nav) && $active_nav === 'about' ? 'bg-brand-50 text-brand-700 border border-brand-200/80' : 'text-slate-700 hover:bg-slate-50 border border-transparent' ?>">
                    🏢 Tentang Kami
                </a>
                <a href="<?= base_url('contact.php') ?>" class="block px-4 py-2.5 rounded-btn text-sm font-semibold <?= isset($active_nav) && $active_nav === 'contact' ? 'bg-brand-50 text-brand-700 border border-brand-200/80' : 'text-slate-700 hover:bg-slate-50 border border-transparent' ?>">
                    📞 Kontak & Lokasi
                </a>
                <a href="<?= base_url('cart.php') ?>" class="block px-4 py-2.5 rounded-btn text-sm font-semibold text-slate-700 hover:bg-slate-50 border border-transparent">
                    🛒 Halaman Keranjang Belanja
                </a>
            </div>

            <div class="pt-2 border-t border-slate-100">
                <?= ui_button('Hubungi Kami via WhatsApp', [
                    'variant' => 'primary',
                    'size'    => 'md',
                    'href'    => 'https://wa.me/' . preg_replace('/[^0-9]/', '', $settings['whatsapp_number']),
                    'target'  => '_blank',
                    'icon'    => 'message-circle',
                    'class'   => 'w-full',
                ]) ?>
            </div>
        </div>
    </header>

    <!-- Slide-over Cart Drawer -->
    <?php require_once __DIR__ . '/cart_drawer.php'; ?>

    <main class="flex-grow">
