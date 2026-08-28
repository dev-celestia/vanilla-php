<!-- Slide-Over Shopping Cart Drawer (Alpine.js) -->
<div 
    x-cloak 
    x-show="$store.cart.isOpen" 
    class="relative z-50" 
    aria-labelledby="slide-over-title" 
    role="dialog" 
    aria-modal="true">
    
    <!-- Background backdrop (Apple translucent blur) -->
    <div 
        x-show="$store.cart.isOpen"
        x-transition:enter="ease-in-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in-out duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="$store.cart.isOpen = false"
        class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity">
    </div>

    <div class="fixed inset-0 overflow-hidden">
        <div class="absolute inset-0 overflow-hidden">
            <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                
                <!-- Slide-over panel (Zero Shadow, Crisp Hairline Border) -->
                <div 
                    x-show="$store.cart.isOpen"
                    x-transition:enter="transform transition ease-out duration-300"
                    x-transition:enter-start="translate-x-full"
                    x-transition:enter-end="translate-x-0"
                    x-transition:leave="transform transition ease-in duration-200"
                    x-transition:leave-start="translate-x-0"
                    x-transition:leave-end="translate-x-full"
                    class="pointer-events-auto w-screen max-w-md bg-white border-l border-slate-200 flex flex-col">
                    
                    <!-- Header -->
                    <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-btn bg-brand-50 border border-brand-200/80 text-brand-600 flex items-center justify-center">
                                <i class="ph ph-shopping-bag text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-base font-bold text-slate-900" id="slide-over-title">Keranjang Belanja</h2>
                                <p class="text-xs text-slate-500">
                                    <span x-text="$store.cart.count">0</span> item dipilih
                                </p>
                            </div>
                        </div>
                        <button 
                            type="button" 
                            @click="$store.cart.isOpen = false"
                            class="p-2 rounded-btn text-slate-400 hover:text-slate-600 hover:bg-slate-100 border border-transparent transition apple-tap">
                            <i class="ph ph-x text-lg"></i>
                        </button>
                    </div>

                    <!-- Cart Content List -->
                    <div class="flex-1 overflow-y-auto p-5 sm:p-6 space-y-4">
                        
                        <!-- Empty State -->
                        <template x-if="$store.cart.items.length === 0">
                            <div class="text-center py-16 px-4">
                                <div class="w-16 h-16 rounded-full bg-slate-100 border border-slate-200/80 text-slate-400 flex items-center justify-center mx-auto mb-4">
                                    <i class="ph ph-shopping-cart text-3xl"></i>
                                </div>
                                <h3 class="text-base font-bold text-slate-800 mb-1">Keranjang Masih Kosong</h3>
                                <p class="text-xs text-slate-500 max-w-xs mx-auto mb-6">Yuk temukan produk-produk menarik pilihan kami dan tambahkan ke keranjang.</p>
                                <button 
                                    type="button" 
                                    @click="$store.cart.isOpen = false"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-btn bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold transition border border-brand-500/20 apple-tap">
                                    <span>Mulai Belanja Sekarang</span>
                                    <i class="ph ph-arrow-right text-xs"></i>
                                </button>
                            </div>
                        </template>

                        <!-- Items List -->
                        <template x-for="item in $store.cart.items" :key="item.id">
                            <div class="flex gap-4 p-3.5 rounded-card bg-slate-50/80 border border-slate-200/80 hover:border-slate-300 transition">
                                <!-- Thumbnail -->
                                <img 
                                    :src="item.image || 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=200&auto=format&fit=crop&q=60'" 
                                    :alt="item.name" 
                                    class="w-16 h-16 rounded-btn object-cover border border-slate-200 flex-shrink-0 bg-white"
                                >

                                <div class="flex-1 min-w-0 flex flex-col justify-between">
                                    <div class="flex items-start justify-between gap-2">
                                        <h4 class="text-xs font-bold text-slate-900 truncate leading-snug" x-text="item.name"></h4>
                                        <button 
                                            type="button" 
                                            @click="$store.cart.removeItem(item.id)" 
                                            class="text-slate-400 hover:text-rose-500 transition p-1 apple-tap" 
                                            title="Hapus">
                                            <i class="ph ph-trash text-sm"></i>
                                        </button>
                                    </div>

                                    <div class="flex items-center justify-between mt-2">
                                        <span class="text-xs font-bold text-brand-600" x-text="$store.cart.formatRupiah(item.price)"></span>

                                        <!-- Qty control -->
                                        <div class="flex items-center border border-slate-200/90 rounded-btn bg-white overflow-hidden">
                                            <button 
                                                type="button" 
                                                @click="$store.cart.updateQty(item.id, -1)" 
                                                class="px-2.5 py-1 text-slate-600 hover:bg-slate-100 font-bold text-xs transition apple-tap">
                                                -
                                            </button>
                                            <span class="px-2.5 py-1 text-xs font-bold text-slate-800" x-text="item.qty"></span>
                                            <button 
                                                type="button" 
                                                @click="$store.cart.updateQty(item.id, 1)" 
                                                class="px-2.5 py-1 text-slate-600 hover:bg-slate-100 font-bold text-xs transition apple-tap">
                                                +
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Footer / Checkout Actions -->
                    <template x-if="$store.cart.items.length > 0">
                        <div class="p-5 sm:p-6 border-t border-slate-100 bg-slate-50/70 space-y-4">
                            <!-- Subtotal calculation -->
                            <div class="space-y-1.5">
                                <div class="flex justify-between text-xs text-slate-500">
                                    <span>Subtotal Produk</span>
                                    <span class="font-medium text-slate-700" x-text="$store.cart.formatRupiah($store.cart.subtotal)"></span>
                                </div>
                                <div class="flex justify-between text-sm font-extrabold text-slate-900 pt-1 border-t border-slate-200/80">
                                    <span>Total Estimasi</span>
                                    <span class="text-brand-600 text-base" x-text="$store.cart.formatRupiah($store.cart.subtotal)"></span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <?= ui_button('Lihat Rincian', [
                                    'variant' => 'secondary',
                                    'size'    => 'sm',
                                    'href'    => base_url('cart.php'),
                                    'icon'    => 'eye',
                                    'class'   => 'w-full text-center',
                                ]) ?>
                                <?= ui_button('Pesan via WA', [
                                    'variant' => 'primary',
                                    'size'    => 'sm',
                                    'href'    => base_url('checkout.php'),
                                    'icon'    => 'paper-plane-tilt',
                                    'class'   => 'w-full text-center',
                                ]) ?>
                            </div>

                            <p class="text-[11px] text-slate-400 text-center">
                                🔒 Pesanan akan diteruskan langsung ke WhatsApp Admin resmi.
                            </p>
                        </div>
                    </template>

                </div>
            </div>
        </div>
    </div>
</div>
