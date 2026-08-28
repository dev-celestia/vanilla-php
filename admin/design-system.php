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

<div class="max-w-6xl mx-auto space-y-10">

    <!-- Overview Banner -->
    <div class="rounded-card bg-white border border-slate-200/80 p-6 sm:p-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <?= ui_badge('Apple Design System', 'brand', ['dot' => true]) ?>
                    <?= ui_badge('Zero Shadow', 'neutral') ?>
                    <?= ui_badge('Ponytail Core', 'success') ?>
                </div>
                <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Primitive UI Components & Token Guide</h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-2xl leading-relaxed">
                    Koleksi komponen primitif ringan Native PHP yang dibangun di atas standar Apple Human Interface (translucent materials, tactile pointer feedback, hairline borders, dan 100% zero shadow).
                </p>
            </div>
            <div>
                <?= ui_button('Atur Tema di Settings', [
                    'variant' => 'outline',
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
                <span class="text-xs font-extrabold text-emerald-600 mt-0.5 block">0px (Flat Crisp Border)</span>
            </div>
            <div class="p-3.5 rounded-btn bg-slate-50 border border-slate-200/70">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Tactile Motion</span>
                <span class="text-xs font-extrabold text-slate-800 mt-0.5 block">Scale 0.98 + Ease-Apple</span>
            </div>
        </div>
    </div>

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
                <span>2. Badges & Status Chips (`ui_badge`)</span>
            </h3>
            <p class="text-xs text-slate-500">Label status clean dengan dot indicator dan palet warna semantik.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <?= ui_badge('Brand Primary', 'brand', ['dot' => true]) ?>
            <?= ui_badge('Neutral Default', 'neutral', ['dot' => true]) ?>
            <?= ui_badge('Pesanan Selesai', 'success', ['dot' => true]) ?>
            <?= ui_badge('Menunggu Pembayaran', 'warning', ['dot' => true]) ?>
            <?= ui_badge('Dibatalkan / Stok Habis', 'danger', ['dot' => true]) ?>
            <?= ui_badge('Informasi Baru', 'info', ['dot' => true]) ?>
            <?= ui_badge('Promo Spesial', 'dark', ['icon' => 'sparkles']) ?>
        </div>
    </div>

    <!-- 3. Form Inputs & Controls Showcase -->
    <div class="rounded-card bg-white border border-slate-200/80 p-6 sm:p-8 space-y-6">
        <div class="border-b border-slate-100 pb-3">
            <h3 class="text-base font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                <i data-lucide="edit-3" class="w-4 h-4 text-brand-600"></i>
                <span>3. Form Input Primitives</span>
            </h3>
            <p class="text-xs text-slate-500">Hairline border, ring-2 fokus warna brand, support icon prefix, helper, dan pesan error.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <?= ui_input('demo_text', [
                'label'       => 'Input Teks Standar',
                'placeholder' => 'Ketik sesuatu...',
                'helper'      => 'Helper text informatif di bawah input.',
            ]) ?>

            <?= ui_input('demo_icon', [
                'label'       => 'Input dengan Icon Prefix',
                'placeholder' => 'Cari produk...',
                'icon'        => 'search',
                'value'       => 'Headphone Wireless ANC',
            ]) ?>

            <?= ui_input('demo_error', [
                'label'       => 'Input Validasi Error',
                'placeholder' => 'Email...',
                'value'       => 'email-salah@format',
                'error'       => 'Alamat email tidak valid.',
            ]) ?>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4 border-t border-slate-100">
            <?= ui_select('demo_select', [
                '1' => 'Pilihan Opsi Pertama',
                '2' => 'Pilihan Opsi Kedua (Default)',
                '3' => 'Pilihan Opsi Ketiga',
            ], [
                'label'    => 'Select Dropdown Primitive',
                'selected' => '2',
            ]) ?>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2">Toggle & Switches</label>
                <div class="space-y-3 pt-1">
                    <?= ui_toggle('demo_toggle_1', 'Aktifkan Notifikasi WhatsApp Otomatis', true, [
                        'helper' => 'Kirim pesan konfirmasi langsung ke pelanggan saat checkout.'
                    ]) ?>
                    <?= ui_toggle('demo_toggle_2', 'Tampilkan Banner Promo di Beranda', false, [
                        'helper' => 'Nonaktifkan jika tidak ada event diskon khusus.'
                    ]) ?>
                </div>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100">
            <?= ui_textarea('demo_textarea', [
                'label'       => 'Textarea Primitive',
                'placeholder' => 'Tulis catatan atau deskripsi panjang di sini...',
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

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
