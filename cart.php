<?php
/**
 * Shopping Cart Page
 */
$active_nav = 'cart';
$page_title = 'Keranjang Belanja - ' . get_settings()['store_name'];
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/helpers/format.php';
require_once __DIR__ . '/includes/header.php';
?>

<div class="bg-white border-b border-slate-200/80 py-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Keranjang Belanja Anda</h1>
        <p class="text-xs text-slate-500 mt-1">Periksa kembali daftar produk pesanan sebelum melanjutkan ke WhatsApp Checkout.</p>
    </div>
</div>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    
    <!-- Empty Cart State -->
    <div x-cloak x-show="$store.cart.items.length === 0">
        <?= ui_empty_state(
            'Keranjang Belanja Anda Masih Kosong',
            'Anda belum menambahkan produk apapun. Jelajahi katalog kami untuk menemukan berbagai pilihan produk menarik!',
            [
                'icon'       => 'shopping-bag',
                'buttonText' => 'Mulai Belanja Sekarang',
                'buttonHref' => base_url(),
                'buttonIcon' => 'shopping-cart',
            ]
        ) ?>
    </div>

    <!-- Active Cart Items -->
    <div x-cloak x-show="$store.cart.items.length > 0" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left: Items List -->
        <div class="lg:col-span-8 space-y-4">
            
            <div class="bg-white rounded-card border border-slate-200/80 overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <span class="text-xs font-bold text-slate-700 tracking-tight">Daftar Produk (<span x-text="$store.cart.count"></span> item)</span>
                    <button 
                        type="button" 
                        @click="if(confirm('Yakin ingin mengosongkan keranjang belanja?')) $store.cart.clearCart()"
                        class="text-xs font-bold text-rose-500 hover:text-rose-700 flex items-center gap-1 transition apple-tap">
                        <i class="ph ph-trash text-sm"></i>
                        <span>Kosongkan Keranjang</span>
                    </button>
                </div>

                <div class="divide-y divide-slate-100">
                    <template x-for="item in $store.cart.items" :key="item.id">
                        <div class="p-5 sm:p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            
                            <div class="flex items-center gap-4 min-w-0">
                                <img 
                                    :src="item.image || 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=200&auto=format&fit=crop&q=60'" 
                                    :alt="item.name" 
                                    class="w-16 h-16 sm:w-20 sm:h-20 rounded-btn object-cover border border-slate-200 bg-white flex-shrink-0"
                                >
                                <div class="min-w-0">
                                    <h3 class="font-bold text-sm text-slate-900 truncate tracking-tight" x-text="item.name"></h3>
                                    <p class="text-xs font-bold text-brand-600 mt-1" x-text="$store.cart.formatRupiah(item.price)"></p>
                                    <span class="text-[11px] text-slate-400">Maksimal stok: <span x-text="item.stock"></span></span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between w-full sm:w-auto gap-6 mt-2 sm:mt-0">
                                <!-- Qty controls -->
                                <div class="flex items-center border border-slate-200/90 rounded-btn bg-white overflow-hidden">
                                    <button 
                                        type="button" 
                                        @click="$store.cart.updateQty(item.id, -1)" 
                                        class="px-3 py-1.5 text-slate-600 hover:bg-slate-100 font-bold text-xs transition apple-tap">
                                        -
                                    </button>
                                    <span class="px-4 py-1.5 text-xs font-bold text-slate-800" x-text="item.qty"></span>
                                    <button 
                                        type="button" 
                                        @click="$store.cart.updateQty(item.id, 1)" 
                                        class="px-3 py-1.5 text-slate-600 hover:bg-slate-100 font-bold text-xs transition apple-tap">
                                        +
                                    </button>
                                </div>

                                <!-- Subtotal per item -->
                                <div class="text-right min-w-24">
                                    <span class="text-xs text-slate-400 block sm:hidden">Total:</span>
                                    <span class="text-sm font-extrabold text-slate-900 tracking-tight" x-text="$store.cart.formatRupiah(item.price * item.qty)"></span>
                                </div>

                                <!-- Delete button -->
                                <button 
                                    type="button" 
                                    @click="$store.cart.removeItem(item.id)" 
                                    class="p-2 rounded-btn text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition apple-tap" 
                                    title="Hapus produk">
                                    <i class="ph ph-trash text-base"></i>
                                </button>
                            </div>

                        </div>
                    </template>
                </div>
            </div>

            <!-- Back to shop link -->
            <div class="pt-2">
                <a href="<?= base_url() ?>" class="inline-flex items-center gap-2 text-xs font-bold text-brand-600 hover:text-brand-700">
                    <i class="ph ph-arrow-left text-xs"></i>
                    <span>Tambah Produk Lainnya dari Katalog</span>
                </a>
            </div>

        </div>

        <!-- Right: Summary Card -->
        <div class="lg:col-span-4">
            <div class="bg-white rounded-card border border-slate-200/80 p-6 space-y-6 sticky top-28">
                <h3 class="font-extrabold text-base text-slate-900 border-b border-slate-100 pb-3 tracking-tight">Ringkasan Belanja</h3>

                <div class="space-y-3 text-xs">
                    <div class="flex justify-between text-slate-500">
                        <span>Total Jumlah Item</span>
                        <span class="font-bold text-slate-800" x-text="$store.cart.count + ' Barang'"></span>
                    </div>
                    <div class="flex justify-between text-slate-500">
                        <span>Subtotal Produk</span>
                        <span class="font-bold text-slate-800" x-text="$store.cart.formatRupiah($store.cart.subtotal)"></span>
                    </div>
                    <div class="flex justify-between text-slate-500">
                        <span>Ongkos Kirim</span>
                        <span class="text-brand-600 font-semibold">Dihitung oleh Admin di WA</span>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex justify-between items-baseline">
                        <span class="text-sm font-extrabold text-slate-900">Total Pembayaran</span>
                        <span class="text-xl font-black text-brand-600 tracking-tight" x-text="$store.cart.formatRupiah($store.cart.subtotal)"></span>
                    </div>
                </div>

                <?= ui_button('Lanjut ke Formulir Checkout', [
                    'variant' => 'primary',
                    'size'    => 'lg',
                    'href'    => base_url('checkout.php'),
                    'icon'    => 'paper-plane-tilt',
                    'class'   => 'w-full text-center',
                ]) ?>

                <div class="pt-2 border-t border-slate-100 text-[11px] text-slate-400 space-y-2">
                    <div class="flex items-center gap-2">
                        <i class="ph ph-shield-check text-base text-brand-600 flex-shrink-0"></i>
                        <span>Transaksi Terhubung Langsung ke WhatsApp</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="ph ph-lock text-base text-slate-400 flex-shrink-0"></i>
                        <span>Data Anda Aman dan Dijamin Kerahasiaannya</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
