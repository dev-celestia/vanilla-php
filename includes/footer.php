<?php
/**
 * Main Storefront Footer
 */
$settings = get_settings();
?>
    </main>

    <!-- Floating WhatsApp Direct Chat Widget (Apple Tactile, Zero Shadow, Clean Border) -->
    <a 
        href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $settings['whatsapp_number']) ?>?text=Halo%20Admin,%20saya%20tertarik%20dengan%20produk%20di%20toko%20Anda" 
        target="_blank" 
        class="fixed bottom-6 right-6 z-40 flex items-center gap-2.5 bg-brand-600 hover:bg-brand-700 text-white px-4 py-3 rounded-full border border-brand-400/30 transition-all duration-150 apple-tap group"
        title="Chat Langsung via WhatsApp">
        <div class="relative">
            <i class="ph ph-chat-circle-dots text-2xl"></i>
            <span class="absolute -top-1 -right-1 w-2.5 h-2.5 rounded-full bg-emerald-300 ring-2 ring-brand-600 animate-ping"></span>
        </div>
        <span class="font-bold text-xs sm:text-sm tracking-tight pr-1">Chat WhatsApp</span>
    </a>

    <!-- Footer Section -->
    <footer class="bg-slate-900 text-slate-400 mt-20 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
                
                <!-- Col 1: Store Brand & Info -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-btn bg-brand-600 border border-brand-500/30 flex items-center justify-center text-white">
                            <i class="ph ph-shopping-bag text-xl"></i>
                        </div>
                        <span class="font-extrabold text-lg text-white tracking-tight">
                            <?= sanitize($settings['store_name']) ?>
                        </span>
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        <?= sanitize($settings['store_description']) ?>
                    </p>
                    <div class="flex items-center gap-3 pt-2">
                        <?php if (!empty($settings['instagram_url'])): ?>
                            <a href="<?= sanitize($settings['instagram_url']) ?>" target="_blank" class="w-8 h-8 rounded-btn bg-slate-800 hover:bg-brand-600 hover:text-white border border-slate-700/60 flex items-center justify-center text-slate-300 transition apple-tap" title="Instagram">
                                <i class="ph ph-instagram-logo text-base"></i>
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($settings['facebook_url'])): ?>
                            <a href="<?= sanitize($settings['facebook_url']) ?>" target="_blank" class="w-8 h-8 rounded-btn bg-slate-800 hover:bg-brand-600 hover:text-white border border-slate-700/60 flex items-center justify-center text-slate-300 transition apple-tap" title="Facebook">
                                <i class="ph ph-facebook-logo text-base"></i>
                            </a>
                        <?php endif; ?>
                        <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $settings['whatsapp_number']) ?>" target="_blank" class="w-8 h-8 rounded-btn bg-slate-800 hover:bg-brand-600 hover:text-white border border-slate-700/60 flex items-center justify-center text-slate-300 transition apple-tap" title="WhatsApp">
                            <i class="ph ph-whatsapp-logo text-base"></i>
                        </a>
                    </div>
                </div>

                <!-- Col 2: Quick Links -->
                <div>
                    <h3 class="text-xs font-bold text-white uppercase tracking-wider mb-4">Navigasi Cepat</h3>
                    <ul class="space-y-2.5 text-xs">
                        <li><a href="<?= base_url() ?>" class="hover:text-brand-300 transition flex items-center gap-1.5"><i class="ph ph-caret-right text-brand-400 text-xs"></i> Beranda Utama</a></li>
                        <li><a href="<?= base_url('demo.php') ?>" class="hover:text-brand-300 transition flex items-center gap-1.5"><i class="ph ph-caret-right text-brand-400 text-xs"></i> Demo E-Commerce & WA</a></li>
                        <li><a href="<?= base_url('about.php') ?>" class="hover:text-brand-300 transition flex items-center gap-1.5"><i class="ph ph-caret-right text-brand-400 text-xs"></i> Profil Bisnis & Toko</a></li>
                        <li><a href="<?= base_url('contact.php') ?>" class="hover:text-brand-300 transition flex items-center gap-1.5"><i class="ph ph-caret-right text-brand-400 text-xs"></i> Kontak & Bantuan</a></li>
                        <li><a href="<?= base_url('cart.php') ?>" class="hover:text-brand-300 transition flex items-center gap-1.5"><i class="ph ph-caret-right text-brand-400 text-xs"></i> Keranjang Belanja</a></li>
                        <li><a href="<?= base_url('admin/login.php') ?>" class="hover:text-brand-300 transition flex items-center gap-1.5"><i class="ph ph-caret-right text-brand-400 text-xs"></i> Dashboard Admin</a></li>
                    </ul>
                </div>

                <!-- Col 3: Customer Benefits -->
                <div>
                    <h3 class="text-xs font-bold text-white uppercase tracking-wider mb-4">Keunggulan Layanan</h3>
                    <ul class="space-y-3 text-xs">
                        <li class="flex items-start gap-2.5">
                            <div class="w-6 h-6 rounded-btn bg-brand-500/10 border border-brand-500/20 text-brand-400 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="ph ph-check-circle text-xs"></i>
                            </div>
                            <span>Pemesanan Praktis Langsung Terhubung ke WhatsApp Admin.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <div class="w-6 h-6 rounded-btn bg-brand-500/10 border border-brand-500/20 text-brand-400 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="ph ph-truck text-xs"></i>
                            </div>
                            <span>Pengiriman Aman & Cepat ke Seluruh Wilayah Indonesia.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <div class="w-6 h-6 rounded-btn bg-brand-500/10 border border-brand-500/20 text-brand-400 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="ph ph-shield-check text-xs"></i>
                            </div>
                            <span>Jaminan Produk Original & Berkualitas Terbaik.</span>
                        </li>
                    </ul>
                </div>

                <!-- Col 4: Contact info -->
                <div>
                    <h3 class="text-xs font-bold text-white uppercase tracking-wider mb-4">Informasi Kontak</h3>
                    <ul class="space-y-3 text-xs">
                        <li class="flex items-start gap-2.5">
                            <i class="ph ph-map-pin text-brand-400 text-base flex-shrink-0 mt-0.5"></i>
                            <span><?= sanitize($settings['store_address']) ?></span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <i class="ph ph-phone text-brand-400 text-base flex-shrink-0"></i>
                            <span><?= sanitize($settings['store_phone']) ?></span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <i class="ph ph-envelope-simple text-brand-400 text-base flex-shrink-0"></i>
                            <span><?= sanitize($settings['store_email']) ?></span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <i class="ph ph-clock text-brand-400 text-base flex-shrink-0"></i>
                            <span>Senin - Sabtu: 08:00 - 21:00 WIB</span>
                        </li>
                    </ul>
                </div>

            </div>

            <!-- Bottom Copyright -->
            <div class="mt-12 pt-8 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
                <p>&copy; <?= date('Y') ?> <?= sanitize($settings['store_name']) ?>. Hak Cipta Dilindungi.</p>
                <p class="flex items-center gap-1">
                    <span>Phosphor Icons & Apple Fluid Primitives</span>
                </p>
            </div>
        </div>
    </footer>
</body>
</html>
