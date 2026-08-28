<?php
/**
 * Main Storefront Footer
 */
$settings = get_settings();
?>
    </main>

    <?php 
    $show_wa_chat = (!empty($is_demo_page)) 
        || (isset($active_nav) && in_array($active_nav, ['demo', 'contact'])) 
        || in_array(basename($_SERVER['PHP_SELF'] ?? ''), ['demo.php', 'product.php', 'cart.php', 'checkout.php', 'order-success.php', 'contact.php']);
    ?>
    <?php if ($show_wa_chat): ?>
    <!-- Floating WhatsApp Direct Chat Widget (Apple Tactile, Zero Shadow, Clean Border) -->
    <a 
        href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $settings['whatsapp_number']) ?>?text=Halo%20Admin,%20saya%20tertarik%20dengan%20produk%20di%20toko%20Anda" 
        target="_blank" 
        class="fixed bottom-6 right-6 z-40 flex items-center gap-2.5 bg-brand-600 hover:bg-brand-700 text-white px-4 py-3 rounded-full border border-brand-400/30 transition-all duration-150 apple-tap group"
        title="Chat Langsung via WhatsApp">
        <div class="relative">
            <i class="ph ph-chat-circle-dots text-2xl"></i>
            <span class="absolute -top-1 -right-1 w-2.5 h-2.5 rounded-full bg-brand-300 ring-2 ring-brand-600 animate-ping"></span>
        </div>
        <span class="font-semibold text-xs sm:text-sm tracking-tight pr-1">Chat WhatsApp</span>
    </a>
    <?php endif; ?>

    <!-- Footer Section -->
    <footer class="bg-slate-950 text-slate-400 mt-20 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
                
                <!-- Col 1: Library Brand & Info -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-btn bg-brand-600 border border-brand-500/30 flex items-center justify-center text-white">
                            <i class="ph ph-code-simple text-xl"></i>
                        </div>
                        <span class="font-semibold text-lg text-white tracking-tight">
                            NativePHP UI
                        </span>
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        A lightweight, modern, zero-dependency PHP UI component library and full-stack application starter built on Apple Human Interface principles, Tailwind CSS, and Alpine.js.
                    </p>
                    <div class="flex items-center gap-3 pt-2">
                        <a href="<?= base_url('design-system.php') ?>" class="px-3 py-1.5 rounded-btn bg-slate-900 hover:bg-slate-800 text-slate-300 text-xs font-semibold border border-slate-800 transition apple-tap flex items-center gap-1.5">
                            <i class="ph ph-palette text-brand-400"></i>
                            <span>Token Explorer</span>
                        </a>
                        <a href="https://github.com/dev-celestia/simple-native-php" target="_blank" class="w-8 h-8 rounded-btn bg-slate-900 hover:bg-slate-800 text-slate-300 border border-slate-800 flex items-center justify-center transition apple-tap" title="GitHub Repository">
                            <i class="ph ph-github-logo text-base"></i>
                        </a>
                    </div>
                </div>

                <!-- Col 2: Quick Links -->
                <div>
                    <h3 class="text-xs font-semibold text-white uppercase tracking-wider mb-4">Framework & Library</h3>
                    <ul class="space-y-2.5 text-xs">
                        <li><a href="<?= base_url() ?>" class="hover:text-brand-300 transition flex items-center gap-1.5"><i class="ph ph-caret-right text-brand-400 text-xs"></i> Overview & Architecture</a></li>
                        <li><a href="<?= base_url('design-system.php') ?>" class="hover:text-brand-300 transition flex items-center gap-1.5"><i class="ph ph-caret-right text-brand-400 text-xs"></i> Design System & Tokens</a></li>
                        <li><a href="<?= base_url('components.php') ?>" class="hover:text-brand-300 transition flex items-center gap-1.5"><i class="ph ph-caret-right text-brand-400 text-xs"></i> Component Primitives</a></li>
                        <li><a href="<?= base_url('demo.php') ?>" class="hover:text-brand-300 transition flex items-center gap-1.5"><i class="ph ph-caret-right text-brand-400 text-xs"></i> E-Commerce Demo Store</a></li>
                        <li><a href="<?= base_url('about.php') ?>" class="hover:text-brand-300 transition flex items-center gap-1.5"><i class="ph ph-caret-right text-brand-400 text-xs"></i> Performance Philosophy</a></li>
                        <li><a href="<?= base_url('admin/login.php') ?>" class="hover:text-brand-300 transition flex items-center gap-1.5"><i class="ph ph-caret-right text-brand-400 text-xs"></i> Admin Panel</a></li>
                    </ul>
                </div>

                <!-- Col 3: Architecture Highlights -->
                <div>
                    <h3 class="text-xs font-semibold text-white uppercase tracking-wider mb-4">Core Principles</h3>
                    <ul class="space-y-3 text-xs">
                        <li class="flex items-start gap-2.5">
                            <div class="w-6 h-6 rounded-btn bg-brand-500/10 border border-brand-500/20 text-brand-400 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="ph ph-lightning text-xs"></i>
                            </div>
                            <span>Sub-50ms TTFB execution with zero heavy vendor autoloading.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <div class="w-6 h-6 rounded-btn bg-brand-500/10 border border-brand-500/20 text-brand-400 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="ph ph-hand-pointing text-xs"></i>
                            </div>
                            <span>Apple Human Interface pointer-down response & tactile active scaling.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <div class="w-6 h-6 rounded-btn bg-brand-500/10 border border-brand-500/20 text-brand-400 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="ph ph-drop text-xs"></i>
                            </div>
                            <span>Translucent materials with backdrop blur & hairline borders.</span>
                        </li>
                    </ul>
                </div>

                <!-- Col 4: Community & Contact info -->
                <div>
                    <h3 class="text-xs font-semibold text-white uppercase tracking-wider mb-4">Support & Community</h3>
                    <ul class="space-y-3 text-xs">
                        <li class="flex items-start gap-2.5">
                            <i class="ph ph-map-pin text-brand-400 text-base flex-shrink-0 mt-0.5"></i>
                            <span><?= sanitize($settings['store_address'] ?? 'Global Open Source Community') ?></span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <i class="ph ph-phone text-brand-400 text-base flex-shrink-0"></i>
                            <span><?= sanitize($settings['store_phone'] ?? '+62 812-3456-7890') ?></span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <i class="ph ph-envelope-simple text-brand-400 text-base flex-shrink-0"></i>
                            <span><?= sanitize($settings['store_email'] ?? 'support@nativephp-ui.dev') ?></span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <i class="ph ph-check-circle text-brand-400 text-base flex-shrink-0"></i>
                            <span>100% Shared Hosting & VPS Ready</span>
                        </li>
                    </ul>
                </div>

            </div>

            <!-- Bottom Copyright -->
            <div class="mt-12 pt-8 border-t border-slate-800/80 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
                <p>&copy; <?= date('Y') ?> NativePHP UI Framework & Component Kit. MIT License.</p>
                <p class="flex items-center gap-2">
                    <span>Phosphor Icons</span>
                    <span>•</span>
                    <span>Tailwind CSS</span>
                    <span>•</span>
                    <span>Alpine.js</span>
                </p>
            </div>
        </div>
    </footer>
</body>
</html>
