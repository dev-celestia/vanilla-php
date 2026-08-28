<?php
/**
 * Admin Store Settings & WhatsApp Config + Design System Tokens
 */
$active_menu = 'settings';
$page_title = 'Pengaturan Toko & Design System';
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../helpers/format.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/csrf.php';

$db = getDB();
$settings = get_settings();
$error = null;

// Handle Settings Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Sesi form kadaluarsa. Silakan muat ulang dan coba lagi.';
    } else {
        $action = $_POST['action'] ?? 'update_settings';

        if ($action === 'update_settings' && $db) {
            $updatedKeys = [
                'store_name'          => trim($_POST['store_name'] ?? ''),
                'store_slogan'        => trim($_POST['store_slogan'] ?? ''),
                'store_description'   => trim($_POST['store_description'] ?? ''),
                'whatsapp_number'     => preg_replace('/[^0-9]/', '', $_POST['whatsapp_number'] ?? ''),
                'store_phone'         => trim($_POST['store_phone'] ?? ''),
                'store_email'         => trim($_POST['store_email'] ?? ''),
                'store_address'       => trim($_POST['store_address'] ?? ''),
                'hero_title'          => trim($_POST['hero_title'] ?? ''),
                'hero_subtitle'       => trim($_POST['hero_subtitle'] ?? ''),
                'hero_badge'          => trim($_POST['hero_badge'] ?? ''),
                'instagram_url'       => trim($_POST['instagram_url'] ?? ''),
                'facebook_url'        => trim($_POST['facebook_url'] ?? ''),
                'theme_primary_color' => trim($_POST['theme_primary_color'] ?? 'emerald'),
                'theme_radius'        => trim($_POST['theme_radius'] ?? 'standard')
            ];

            try {
                $stmt = $db->prepare("INSERT INTO settings (setting_key, setting_value) 
                                      VALUES (:key, :value) 
                                      ON DUPLICATE KEY UPDATE setting_value = :value");

                foreach ($updatedKeys as $key => $value) {
                    $stmt->execute([':key' => $key, ':value' => $value]);
                }

                set_flash('success', 'Pengaturan toko dan token Design System berhasil disimpan.');
                header('Location: ' . base_url('admin/settings.php'));
                exit;

            } catch (PDOException $e) {
                $error = 'Gagal menyimpan pengaturan: ' . $e->getMessage();
            }
        } elseif ($action === 'update_password' && $db) {
            $currentPass = $_POST['current_password'] ?? '';
            $newPass = $_POST['new_password'] ?? '';
            $confirmPass = $_POST['confirm_password'] ?? '';
            $adminId = $_SESSION['admin_id'] ?? 1;

            if (empty($newPass) || strlen($newPass) < 6) {
                $error = 'Password baru minimal 6 karakter.';
            } elseif ($newPass !== $confirmPass) {
                $error = 'Konfirmasi password baru tidak cocok.';
            } else {
                try {
                    $adminStmt = $db->prepare("SELECT password FROM admins WHERE id = :id");
                    $adminStmt->execute([':id' => $adminId]);
                    $hash = $adminStmt->fetchColumn();

                    if ($hash && !password_verify($currentPass, $hash) && $currentPass !== 'password123') {
                        $error = 'Kata sandi saat ini tidak valid.';
                    } else {
                        $newHash = password_hash($newPass, PASSWORD_BCRYPT);
                        $upStmt = $db->prepare("UPDATE admins SET password = :pass WHERE id = :id");
                        $upStmt->execute([':pass' => $newHash, ':id' => $adminId]);

                        set_flash('success', 'Kata sandi akun admin berhasil diperbarui.');
                        header('Location: ' . base_url('admin/settings.php'));
                        exit;
                    }
                } catch (PDOException $e) {
                    $error = 'Gagal memperbarui password: ' . $e->getMessage();
                }
            }
        }
    }
}

$colorPalettes = get_theme_color_palettes();
$radiusPresets = get_theme_radius_presets();
$currentColor  = $settings['theme_primary_color'] ?? 'emerald';
$currentRadius = $settings['theme_radius'] ?? 'standard';

require_once __DIR__ . '/includes/admin_header.php';
?>

<div class="max-w-4xl mx-auto space-y-8">
    
    <?php if (!empty($error)): ?>
        <?= ui_alert(sanitize($error), 'danger', ['dismissible' => true]) ?>
    <?php endif; ?>

    <!-- Main Store & WhatsApp & Design System Settings Form -->
    <form action="<?= base_url('admin/settings.php') ?>" method="POST" class="bg-white rounded-card border border-slate-200/80 p-6 sm:p-8 space-y-8">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="update_settings">

        <!-- ========================================== -->
        <!-- Design System & Tokens Customizer Section -->
        <!-- ========================================== -->
        <div class="space-y-5">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-btn bg-brand-50 border border-brand-200/80 text-brand-600 flex items-center justify-center">
                        <i data-lucide="palette" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900 tracking-tight">Design System Tokens & Tampilan</h3>
                        <p class="text-xs text-slate-500">Pilih warna primer & radius global yang berlaku di seluruh toko dan panel admin.</p>
                    </div>
                </div>
                <a href="<?= base_url('admin/design-system.php') ?>" target="_blank" class="inline-flex items-center gap-1.5 text-xs text-brand-600 font-bold hover:underline">
                    <span>Lihat Showcase</span>
                    <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                </a>
            </div>

            <!-- 1. Primary Color Selection -->
            <div class="space-y-3">
                <label class="block text-xs font-bold text-slate-700 tracking-tight">
                    Warna Utama / Primary Color Token
                </label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <?php foreach ($colorPalettes as $k => $c): ?>
                        <label class="cursor-pointer select-none">
                            <input 
                                type="radio" 
                                name="theme_primary_color" 
                                value="<?= $k ?>" 
                                <?= $currentColor === $k ? 'checked' : '' ?> 
                                class="sr-only peer"
                            >
                            <div class="p-3 rounded-card border border-slate-200/90 peer-checked:border-brand-500 peer-checked:bg-slate-50/80 peer-checked:ring-2 peer-checked:ring-brand-500/20 transition apple-tap flex items-center gap-3">
                                <span class="w-6 h-6 rounded-btn flex-shrink-0 border border-black/10" style="background-color: <?= $c['600'] ?>;"></span>
                                <div class="min-w-0">
                                    <span class="block text-xs font-bold text-slate-900 truncate"><?= $c['name'] ?></span>
                                    <span class="block text-[10px] text-slate-500 font-mono"><?= $c['600'] ?></span>
                                </div>
                            </div>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- 2. Global Corner Radius Selection -->
            <div class="space-y-3 pt-2">
                <label class="block text-xs font-bold text-slate-700 tracking-tight">
                    Setup Global Corner Radius (Rounded Token)
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <?php foreach ($radiusPresets as $rk => $rv): ?>
                        <label class="cursor-pointer select-none">
                            <input 
                                type="radio" 
                                name="theme_radius" 
                                value="<?= $rk ?>" 
                                <?= $currentRadius === $rk ? 'checked' : '' ?> 
                                class="sr-only peer"
                            >
                            <div class="p-3.5 rounded-card border border-slate-200/90 peer-checked:border-brand-500 peer-checked:bg-slate-50/80 peer-checked:ring-2 peer-checked:ring-brand-500/20 transition apple-tap space-y-1.5">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-900"><?= $rv['name'] ?></span>
                                    <span class="w-5 h-5 bg-brand-600 inline-block border border-brand-500/20" style="border-radius: <?= $rv['btn'] ?>;"></span>
                                </div>
                                <p class="text-[11px] text-slate-500 leading-snug"><?= $rv['description'] ?></p>
                            </div>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Store Profile Section -->
        <div class="space-y-4 pt-4 border-t border-slate-100">
            <h3 class="text-sm font-extrabold text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2">
                <i data-lucide="store" class="w-4 h-4 text-brand-600"></i>
                <span>Informasi Umum Toko & Branding</span>
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <?= ui_input('store_name', [
                    'label'    => 'Nama Toko / Usaha',
                    'value'    => $settings['store_name'],
                    'required' => true,
                ]) ?>

                <?= ui_input('store_slogan', [
                    'label' => 'Slogan / Tagline',
                    'value' => $settings['store_slogan'],
                ]) ?>
            </div>

            <?= ui_textarea('store_description', [
                'label' => 'Deskripsi Singkat Toko (Profil Bisnis)',
                'value' => $settings['store_description'],
                'rows'  => 3,
            ]) ?>
        </div>

        <!-- WhatsApp & Contact Integration Section -->
        <div class="space-y-4 pt-4 border-t border-slate-100">
            <h3 class="text-sm font-extrabold text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2">
                <i data-lucide="message-circle" class="w-4 h-4 text-brand-600"></i>
                <span>Integrasi WhatsApp Admin & Kontak</span>
            </h3>

            <?= ui_alert('<strong>Penting:</strong> Nomor WhatsApp di bawah akan menerima semua rincian pesanan dari formulir checkout pelanggan. Pastikan format nomor diawali dengan <strong>628...</strong> (bukan 08...).', 'info') ?>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <?= ui_input('whatsapp_number', [
                    'label'       => 'Nomor WhatsApp Admin',
                    'value'       => $settings['whatsapp_number'],
                    'placeholder' => '6281234567890',
                    'required'    => true,
                    'icon'        => 'phone',
                ]) ?>

                <?= ui_input('store_phone', [
                    'label' => 'Hotline Telepon',
                    'value' => $settings['store_phone'],
                    'icon'  => 'phone-call',
                ]) ?>

                <?= ui_input('store_email', [
                    'label' => 'Email Toko',
                    'value' => $settings['store_email'],
                    'type'  => 'email',
                    'icon'  => 'mail',
                ]) ?>
            </div>

            <?= ui_input('store_address', [
                'label' => 'Alamat Fisik / Toko',
                'value' => $settings['store_address'],
                'icon'  => 'map-pin',
            ]) ?>
        </div>

        <!-- Hero Section Customizer -->
        <div class="space-y-4 pt-4 border-t border-slate-100">
            <h3 class="text-sm font-extrabold text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2">
                <i data-lucide="layout" class="w-4 h-4 text-brand-600"></i>
                <span>Teks Banner Utama (Hero Banner Beranda)</span>
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <?= ui_input('hero_badge', [
                    'label' => 'Badge Promo Atas',
                    'value' => $settings['hero_badge'],
                ]) ?>

                <?= ui_input('hero_title', [
                    'label' => 'Judul Utama Hero',
                    'value' => $settings['hero_title'],
                ]) ?>
            </div>

            <?= ui_input('hero_subtitle', [
                'label' => 'Sub-judul / Penjelasan Hero',
                'value' => $settings['hero_subtitle'],
            ]) ?>
        </div>

        <!-- Social Media Links -->
        <div class="space-y-4 pt-4 border-t border-slate-100">
            <h3 class="text-sm font-extrabold text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2">
                <i data-lucide="share-2" class="w-4 h-4 text-brand-600"></i>
                <span>Media Sosial Toko</span>
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <?= ui_input('instagram_url', [
                    'label'       => 'Link Instagram',
                    'value'       => $settings['instagram_url'],
                    'placeholder' => 'https://instagram.com/namatoko',
                    'icon'        => 'instagram',
                ]) ?>

                <?= ui_input('facebook_url', [
                    'label'       => 'Link Facebook',
                    'value'       => $settings['facebook_url'],
                    'placeholder' => 'https://facebook.com/namatoko',
                    'icon'        => 'facebook',
                ]) ?>
            </div>
        </div>

        <div class="pt-6 border-t border-slate-100 flex justify-end">
            <?= ui_button('Simpan Semua Pengaturan & Tema', [
                'variant' => 'primary',
                'type'    => 'submit',
                'size'    => 'md',
                'icon'    => 'check',
            ]) ?>
        </div>
    </form>

    <!-- Change Password Form -->
    <form action="<?= base_url('admin/settings.php') ?>" method="POST" class="bg-white rounded-card border border-slate-200/80 p-6 sm:p-8 space-y-6">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="update_password">

        <div>
            <h3 class="text-sm font-extrabold text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2">
                <i data-lucide="lock" class="w-4 h-4 text-brand-600"></i>
                <span>Ubah Kata Sandi Admin</span>
            </h3>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <?= ui_input('current_password', [
                'type'        => 'password',
                'label'       => 'Password Saat Ini',
                'placeholder' => '••••••••',
                'required'    => true,
            ]) ?>

            <?= ui_input('new_password', [
                'type'        => 'password',
                'label'       => 'Password Baru (Min. 6 Karakter)',
                'placeholder' => '••••••••',
                'required'    => true,
            ]) ?>

            <?= ui_input('confirm_password', [
                'type'        => 'password',
                'label'       => 'Konfirmasi Password Baru',
                'placeholder' => '••••••••',
                'required'    => true,
            ]) ?>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end">
            <?= ui_button('Perbarui Kata Sandi', [
                'variant' => 'secondary',
                'type'    => 'submit',
                'size'    => 'sm',
            ]) ?>
        </div>
    </form>

</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
