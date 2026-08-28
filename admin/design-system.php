<?php
/**
 * Admin Panel - Design System Primitives Showcase & Living Style Guide
 */
$active_menu = 'design_system';
$page_title = 'Design System Primitives & Tokens';
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../helpers/format.php';
require_once __DIR__ . '/../helpers/auth.php';

$activeTheme = get_active_theme();
$palettes = get_theme_color_palettes();
$radiuses = get_theme_radius_presets();

require_once __DIR__ . '/includes/admin_header.php';
?>

<div class="max-w-6xl mx-auto space-y-10" x-data="{ activeTab: 'showcase' }">

    <!-- Overview Banner -->
    <div class="rounded-card bg-white border border-slate-200/80 p-6 sm:p-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <?= ui_badge('Apple Design System', 'brand', ['dot' => true]) ?>
                    <?= ui_badge('Zero Shadow', 'neutral') ?>
                    <?= ui_badge('Ponytail Core', 'success') ?>
                </div>
                <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Primitive UI Components & Design Tokens</h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-2xl leading-relaxed">
                    Koleksi komponen primitif ringan Native PHP yang dibangun di atas standar Apple Human Interface (translucent materials, tactile pointer feedback, hairline borders, dan 100% zero shadow).
                </p>
            </div>
            <div class="flex items-center gap-2">
                <?= ui_button('Buka Settings Tema', [
                    'variant' => 'primary',
                    'size'    => 'sm',
                    'href'    => base_url('admin/settings.php'),
                    'icon'    => 'sliders',
                ]) ?>
            </div>
        </div>

        <!-- Active Tokens Summary Bar -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-6 pt-6 border-t border-slate-100">
            <div class="p-3.5 rounded-btn bg-slate-50 border border-slate-200/70">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Primary Palette</span>
                <span class="text-xs font-extrabold text-slate-800 flex items-center gap-1.5 mt-0.5">
                    <span class="w-3 h-3 rounded-full bg-brand-600 inline-block border border-black/10"></span>
                    <?= $activeTheme['palette']['name'] ?>
                </span>
            </div>
            <div class="p-3.5 rounded-btn bg-slate-50 border border-slate-200/70">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Corner Radius</span>
                <span class="text-xs font-extrabold text-slate-800 mt-0.5 block"><?= $activeTheme['radius']['name'] ?></span>
            </div>
            <div class="p-3.5 rounded-btn bg-slate-50 border border-slate-200/70">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Shadow Policy</span>
                <span class="text-xs font-extrabold text-brand-600 mt-0.5 block">0px (Flat Crisp Border)</span>
            </div>
            <div class="p-3.5 rounded-btn bg-slate-50 border border-slate-200/70">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Tactile Motion</span>
                <span class="text-xs font-extrabold text-slate-800 mt-0.5 block">Scale 0.98 + Ease-Apple</span>
            </div>
        </div>

        <!-- Nav Tabs (Showcase vs Customization Guide) -->
        <div class="flex items-center gap-2 mt-6 pt-6 border-t border-slate-100">
            <button 
                type="button" 
                @click="activeTab = 'showcase'"
                :class="activeTab === 'showcase' ? 'bg-brand-600 text-white font-extrabold' : 'bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold'"
                class="px-4 py-2 rounded-btn text-xs transition apple-tap flex items-center gap-2">
                <i data-lucide="layout-grid" class="w-3.5 h-3.5"></i>
                <span>Komponen Primitif (Showcase)</span>
            </button>

            <button 
                type="button" 
                @click="activeTab = 'guide'"
                :class="activeTab === 'guide' ? 'bg-brand-600 text-white font-extrabold' : 'bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold'"
                class="px-4 py-2 rounded-btn text-xs transition apple-tap flex items-center gap-2">
                <i data-lucide="book-open" class="w-3.5 h-3.5"></i>
                <span>📖 Panduan Kustomisasi Tema (Guide)</span>
            </button>
        </div>
    </div>

    <!-- TAB 1: SHOWCASE CONTENT -->
    <div x-show="activeTab === 'showcase'" class="space-y-10">

        <!-- 1. Buttons Primitive Showcase -->
        <div class="rounded-card bg-white border border-slate-200/80 p-6 sm:p-8 space-y-6">
            <div class="border-b border-slate-100 pb-3">
                <h3 class="text-base font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                    <i data-lucide="mouse-pointer-click" class="w-4 h-4 text-brand-600"></i>
                    <span>1. Button Primitives (`ui_button`)</span>
                </h3>
                <p class="text-xs text-slate-500">Dilengkapi dengan respon pointer-down instan (`apple-tap`), scale physics, dan token rounded.</p>
            </div>

            <div class="space-y-4">
                <h4 class="text-xs font-bold text-slate-600 uppercase tracking-wider">Variants</h4>
                <div class="flex flex-wrap items-center gap-3">
                    <?= ui_button('Primary Action', ['variant' => 'primary', 'icon' => 'check']) ?>
                    <?= ui_button('Secondary', ['variant' => 'secondary', 'icon' => 'sparkles']) ?>
                    <?= ui_button('Outline Brand', ['variant' => 'outline', 'icon' => 'tag']) ?>
                    <?= ui_button('Subtle Tint', ['variant' => 'subtle', 'icon' => 'info']) ?>
                    <?= ui_button('Ghost Button', ['variant' => 'ghost', 'icon' => 'chevron-right', 'iconRight' => null]) ?>
                    <?= ui_button('Danger Action', ['variant' => 'danger', 'icon' => 'trash-2']) ?>
                    <?= ui_button('Disabled State', ['variant' => 'primary', 'disabled' => true]) ?>
                </div>
            </div>

            <div class="space-y-4 pt-4 border-t border-slate-100">
                <h4 class="text-xs font-bold text-slate-600 uppercase tracking-wider">Sizes (`xs`, `sm`, `md`, `lg`)</h4>
                <div class="flex flex-wrap items-center gap-3">
                    <?= ui_button('Extra Small (xs)', ['size' => 'xs', 'icon' => 'plus']) ?>
                    <?= ui_button('Small (sm)', ['size' => 'sm', 'icon' => 'plus']) ?>
                    <?= ui_button('Medium Default (md)', ['size' => 'md', 'icon' => 'shopping-cart']) ?>
                    <?= ui_button('Large Hero (lg)', ['size' => 'lg', 'icon' => 'arrow-right']) ?>
                </div>
            </div>
        </div>

        <!-- 2. Badges & Chips Showcase -->
        <div class="rounded-card bg-white border border-slate-200/80 p-6 sm:p-8 space-y-6">
            <div class="border-b border-slate-100 pb-3">
                <h3 class="text-base font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                    <i data-lucide="tag" class="w-4 h-4 text-brand-600"></i>
                    <span>2. Badge & Status Primitives (`ui_badge`)</span>
                </h3>
                <p class="text-xs text-slate-500">Label status ringan dengan token rounded badge dan semantic dots.</p>
            </div>

            <div class="space-y-4">
                <h4 class="text-xs font-bold text-slate-600 uppercase tracking-wider">Semantic Badges (With Dot)</h4>
                <div class="flex flex-wrap items-center gap-3">
                    <?= ui_badge('Brand Active', 'brand', ['dot' => true]) ?>
                    <?= ui_badge('Pesanan Selesai', 'success', ['dot' => true]) ?>
                    <?= ui_badge('Menunggu Pembayaran', 'warning', ['dot' => true]) ?>
                    <?= ui_badge('Pesanan Dibatalkan', 'danger', ['dot' => true]) ?>
                    <?= ui_badge('Diproses Ekspedisi', 'info', ['dot' => true]) ?>
                    <?= ui_badge('Draft Nonaktif', 'neutral', ['dot' => true]) ?>
                </div>
            </div>
        </div>

        <!-- 3. Form Input Primitives -->
        <div class="rounded-card bg-white border border-slate-200/80 p-6 sm:p-8 space-y-6">
            <div class="border-b border-slate-100 pb-3">
                <h3 class="text-base font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                    <i data-lucide="edit-3" class="w-4 h-4 text-brand-600"></i>
                    <span>3. Form Input Primitives (`ui_input`, `ui_select`, `ui_toggle`, `ui_textarea`)</span>
                </h3>
                <p class="text-xs text-slate-500">Input flat dengan hairline border, focus ring brand dinamis, helper text, dan state error.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <?= ui_input('demo_name', [
                    'label'       => 'Nama Pelanggan',
                    'placeholder' => 'Contoh: Ahmad Dahlan',
                    'icon'        => 'user',
                    'helper'      => 'Nama penerima paket barang.',
                ]) ?>

                <?= ui_input('demo_error', [
                    'label'       => 'Nomor WhatsApp (State Error)',
                    'placeholder' => '081234567890',
                    'value'       => '08abc',
                    'icon'        => 'phone',
                    'error'       => 'Nomor telepon hanya boleh berisi angka.',
                ]) ?>

                <?= ui_select('demo_select', [
                    '1' => 'Reguler (2-3 Hari)',
                    '2' => 'Next Day (1 Hari)',
                    '3' => 'Instant Courier (Same Day)',
                ], [
                    'label'    => 'Pilih Layanan Pengiriman',
                    'selected' => '1',
                ]) ?>

                <div class="pt-6">
                    <?= ui_toggle('demo_toggle', 'Kirim Notifikasi Otomatis ke WhatsApp', true, [
                        'helper' => 'Sistem akan mengirim konfirmasi invoice secara instan.',
                    ]) ?>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100">
                <?= ui_textarea('demo_textarea', [
                    'label'       => 'Catatan Tambahan',
                    'placeholder' => 'Tulis catatan atau instruksi pengiriman...',
                    'rows'        => 2,
                ]) ?>
            </div>
        </div>

        <!-- 4. Alerts & Notices Showcase -->
        <div class="rounded-card bg-white border border-slate-200/80 p-6 sm:p-8 space-y-6">
            <div class="border-b border-slate-100 pb-3">
                <h3 class="text-base font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                    <i data-lucide="bell" class="w-4 h-4 text-brand-600"></i>
                    <span>4. Alert & Notice Primitives (`ui_alert`)</span>
                </h3>
                <p class="text-xs text-slate-500">Banner notifikasi flat dengan semantic color coding dan tombol dismiss.</p>
            </div>

            <div class="space-y-3">
                <?= ui_alert('Pesanan berhasil dibuat dan diteruskan ke admin WhatsApp.', 'success', [
                    'title'       => 'Sukses!',
                    'dismissible' => true,
                ]) ?>
                <?= ui_alert('Terjadi kesalahan saat memproses formulir. Silakan periksa kembali data Anda.', 'danger', [
                    'title'       => 'Gagal Memproses Data',
                    'dismissible' => true,
                ]) ?>
                <?= ui_alert('Stok produk ini tersisa kurang dari 5 unit di gudang.', 'warning', [
                    'title'       => 'Peringatan Stok Rendah',
                ]) ?>
                <?= ui_alert('Gunakan kode promo <strong>DISKON50</strong> untuk mendapatkan potongan harga spesial.', 'info') ?>
            </div>
        </div>

        <!-- 5. Stat Cards & Metrics Showcase -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-base font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                    <i data-lucide="bar-chart-2" class="w-4 h-4 text-brand-600"></i>
                    <span>5. Stat Cards Primitive (`ui_stat_card`)</span>
                </h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <?= ui_stat_card('Total Produk', '48 Unit', [
                    'icon'      => 'package',
                    'subtitle'  => 'Di 6 kategori aktif',
                    'trend'     => '+12%',
                    'trendType' => 'up',
                ]) ?>
                <?= ui_stat_card('Pesanan Masuk', '128 Transaksi', [
                    'icon'      => 'shopping-cart',
                    'subtitle'  => 'Bulan berjalan',
                    'trend'     => '+24%',
                    'trendType' => 'up',
                ]) ?>
                <?= ui_stat_card('Estimasi Omset', 'Rp 18.500.000', [
                    'icon'      => 'dollar-sign',
                    'subtitle'  => 'Konfirmasi via WA',
                    'trend'     => '+8.4%',
                    'trendType' => 'up',
                ]) ?>
                <?= ui_stat_card('Pelanggan Aktif', '85 Kontak', [
                    'icon'      => 'users',
                    'subtitle'  => 'Database WhatsApp',
                ]) ?>
            </div>
        </div>

        <!-- 6. Avatars & Icon Boxes Showcase -->
        <div class="rounded-card bg-white border border-slate-200/80 p-6 sm:p-8 space-y-6">
            <div class="border-b border-slate-100 pb-3">
                <h3 class="text-base font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                    <i data-lucide="user" class="w-4 h-4 text-brand-600"></i>
                    <span>6. Avatars & Icon Boxes (`ui_avatar`, `ui_icon_box`)</span>
                </h3>
                <p class="text-xs text-slate-500">Avatar dinamis dengan fallback inisial nama dan icon container serbaguna.</p>
            </div>

            <div class="flex flex-wrap items-center gap-4">
                <?= ui_avatar('Admin Toko', ['size' => 'xs']) ?>
                <?= ui_avatar('Budi Santoso', ['size' => 'sm']) ?>
                <?= ui_avatar('Siti Nurhaliza', ['size' => 'md']) ?>
                <?= ui_avatar('Rian Ardiansyah', ['size' => 'lg']) ?>
                <?= ui_avatar('https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80', ['size' => 'lg']) ?>
                
                <div class="h-8 border-r border-slate-200 mx-2"></div>

                <?= ui_icon_box('shopping-bag', 'brand', ['size' => 'sm']) ?>
                <?= ui_icon_box('shield-check', 'primary', ['size' => 'md']) ?>
                <?= ui_icon_box('truck', 'slate', ['size' => 'md']) ?>
                <?= ui_icon_box('message-circle', 'dark', ['size' => 'lg']) ?>
            </div>
        </div>

    </div>

    <!-- TAB 2: DEVELOPER & THEME CUSTOMIZATION GUIDE -->
    <div x-show="activeTab === 'guide'" class="space-y-8">
        
        <!-- Step 1: Admin UI Mode -->
        <div class="rounded-card bg-white border border-slate-200/80 p-6 sm:p-8 space-y-4">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-btn bg-brand-50 text-brand-600 border border-brand-200/80 flex items-center justify-center font-bold text-xs">
                    1
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-slate-900 tracking-tight">Kustomisasi Instan via Admin Settings</h3>
                    <p class="text-xs text-slate-500">Ubah tema visual tanpa menyentuh kode program.</p>
                </div>
            </div>

            <div class="p-4 rounded-btn bg-slate-50 border border-slate-200/80 space-y-2 text-xs text-slate-600 leading-relaxed">
                <p>1. Buka menu <strong>Pengaturan Toko</strong> (<a href="<?= base_url('admin/settings.php') ?>" class="text-brand-600 font-bold hover:underline">/admin/settings.php</a>).</p>
                <p>2. Pilih salah satu dari <strong>8 Palet Warna Primer</strong> (Emerald, Blue, Indigo, Violet, Rose, Amber, Teal, Slate).</p>
                <p>3. Pilih <strong>Preset Corner Radius</strong> (Sharp 0px, Subtle 6px, Standard Apple 12px, Soft 16px, Round 24px, Pill 9999px).</p>
                <p>4. Klik <strong>Simpan Pengaturan & Tema</strong>. Tema baru otomatis diterapkan ke seluruh toko & admin.</p>
            </div>
        </div>

        <!-- Step 2: Adding New Palettes in Code -->
        <div class="rounded-card bg-white border border-slate-200/80 p-6 sm:p-8 space-y-4">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-btn bg-brand-50 text-brand-600 border border-brand-200/80 flex items-center justify-center font-bold text-xs">
                    2
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-slate-900 tracking-tight">Menambah Palet Warna Baru di Kode</h3>
                    <p class="text-xs text-slate-500">Edit file <code>config/theme.php</code> untuk menambahkan skema warna brand kustom.</p>
                </div>
            </div>

            <div class="rounded-btn bg-slate-950 p-4 font-mono text-xs text-slate-200 overflow-x-auto border border-slate-800">
<pre class="text-slate-300">// Buka file: config/theme.php
// Tambahkan entry baru pada fungsi get_theme_color_palettes():

'cyber_orange' => [
    'name' => 'Cyber Orange',
    '50'  => '#fff7ed',
    '100' => '#ffedd5',
    '200' => '#fed7aa',
    '300' => '#fdba74',
    '400' => '#fb923c',
    '500' => '#f97316', // Aksen
    '600' => '#ea580c', // Warna primer tombol & aksen utama
    '700' => '#c2410c', // Hover state
    '800' => '#9a3412',
    '900' => '#7c2d12',
    '950' => '#431407',
],</pre>
            </div>
        </div>

        <!-- Step 3: Available Utility Classes & Tokens -->
        <div class="rounded-card bg-white border border-slate-200/80 p-6 sm:p-8 space-y-4">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-btn bg-brand-50 text-brand-600 border border-brand-200/80 flex items-center justify-center font-bold text-xs">
                    3
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-slate-900 tracking-tight">Token Utility Classes untuk Template Baru</h3>
                    <p class="text-xs text-slate-500">Gunakan class ini di file HTML/PHP agar otomatis mengikuti tema yang aktif.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border border-slate-200/80 rounded-btn overflow-hidden">
                    <thead class="bg-slate-50 border-b border-slate-100 text-slate-400 font-bold uppercase">
                        <tr>
                            <th class="px-4 py-3">Utility Class</th>
                            <th class="px-4 py-3">Fungsi / Deskripsi</th>
                            <th class="px-4 py-3">Contoh Hasil</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        <tr>
                            <td class="px-4 py-3 font-mono text-brand-600 font-bold">bg-brand-600</td>
                            <td class="px-4 py-3">Background warna primer sesuai palet yang aktif</td>
                            <td class="px-4 py-3"><span class="w-4 h-4 rounded-full bg-brand-600 inline-block"></span></td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-mono text-brand-600 font-bold">text-brand-600</td>
                            <td class="px-4 py-3">Warna teks primer tema</td>
                            <td class="px-4 py-3 font-bold text-brand-600">Teks Contoh</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-mono text-brand-600 font-bold">rounded-card</td>
                            <td class="px-4 py-3">Kelengkungan sudut kartu & container</td>
                            <td class="px-4 py-3"><span class="px-2 py-1 bg-slate-200 rounded-card font-mono text-[10px]">card</span></td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-mono text-brand-600 font-bold">rounded-btn</td>
                            <td class="px-4 py-3">Kelengkungan sudut tombol & input</td>
                            <td class="px-4 py-3"><span class="px-2 py-1 bg-slate-200 rounded-btn font-mono text-[10px]">button</span></td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-mono text-brand-600 font-bold">rounded-badge</td>
                            <td class="px-4 py-3">Kelengkungan sudut status label/badge</td>
                            <td class="px-4 py-3"><span class="px-2 py-1 bg-slate-200 rounded-badge font-mono text-[10px]">badge</span></td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-mono text-brand-600 font-bold">apple-tap</td>
                            <td class="px-4 py-3">Respon tekan pointer-down instan fisik Apple (`scale 0.975`)</td>
                            <td class="px-4 py-3"><button class="px-2 py-1 bg-brand-600 text-white rounded-btn text-[10px] apple-tap">Tekan Saya</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Step 4: PHP Component Primitives Cheat-Sheet -->
        <div class="rounded-card bg-white border border-slate-200/80 p-6 sm:p-8 space-y-4">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-btn bg-brand-50 text-brand-600 border border-brand-200/80 flex items-center justify-center font-bold text-xs">
                    4
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-slate-900 tracking-tight">Cheat-Sheet Helper Primitives PHP</h3>
                    <p class="text-xs text-slate-500">Salin fungsi di bawah ini untuk merender komponen secara instan di file PHP manapun.</p>
                </div>
            </div>

            <div class="rounded-btn bg-slate-950 p-4 font-mono text-xs text-slate-200 overflow-x-auto border border-slate-800 space-y-4">
<pre class="text-emerald-400">// 1. Render Tombol / Link</pre>
<pre class="text-slate-300">&lt;?= ui_button('Beli Sekarang', ['variant' => 'primary', 'icon' => 'shopping-cart']) ?&gt;
&lt;?= ui_button('Lihat Detail', ['variant' => 'outline', 'href' => 'product.php?id=1']) ?&gt;</pre>

<pre class="text-emerald-400">// 2. Render Form Input & Toggle</pre>
<pre class="text-slate-300">&lt;?= ui_input('username', ['label' => 'Username', 'placeholder' => 'admin', 'required' => true]) ?&gt;
&lt;?= ui_toggle('is_active', 'Aktifkan Produk', true) ?&gt;</pre>

<pre class="text-emerald-400">// 3. Render Status Badge & Alert</pre>
<pre class="text-slate-300">&lt;?= ui_badge('Pesanan Baru', 'brand', ['dot' => true]) ?&gt;
&lt;?= ui_alert('Perubahan berhasil disimpan.', 'success', ['dismissible' => true]) ?&gt;</pre>

<pre class="text-emerald-400">// 4. Render Stat Metric Card</pre>
<pre class="text-slate-300">&lt;?= ui_stat_card('Total Penjualan', 'Rp 15.000.000', ['icon' => 'banknote', 'trend' => '+12%']) ?&gt;</pre>
            </div>
        </div>

    </div>

</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
