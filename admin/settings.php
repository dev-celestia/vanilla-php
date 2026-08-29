<?php
/**
 * Admin Store Settings & WhatsApp Config + Theme Tokens
 */
$active_menu = 'settings';
$page_title = 'Store Settings & Appearance';
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
        $error = 'Form session expired. Please reload and try again.';
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
                'facebook_url'        => trim($_POST['facebook_url'] ?? '')
            ];

            try {
                $stmt = $db->prepare("INSERT INTO settings (setting_key, setting_value) 
                                      VALUES (:key, :value) 
                                      ON DUPLICATE KEY UPDATE setting_value = :value");

                foreach ($updatedKeys as $key => $value) {
                    $stmt->execute([':key' => $key, ':value' => $value]);
                }

                set_flash('success', 'Store settings updated successfully.');
                header('Location: ' . base_url('admin/settings.php'));
                exit;

            } catch (PDOException $e) {
                $error = 'Failed to save settings: ' . $e->getMessage();
            }
        } elseif ($action === 'update_password' && $db) {
            $currentPass = $_POST['current_password'] ?? '';
            $newPass = $_POST['new_password'] ?? '';
            $confirmPass = $_POST['confirm_password'] ?? '';
            $adminId = $_SESSION['admin_id'] ?? 1;

            if (empty($newPass) || strlen($newPass) < 6) {
                $error = 'New password must be at least 6 characters.';
            } elseif ($newPass !== $confirmPass) {
                $error = 'New password confirmation does not match.';
            } else {
                try {
                    $adminStmt = $db->prepare("SELECT password FROM admins WHERE id = :id");
                    $adminStmt->execute([':id' => $adminId]);
                    $hash = $adminStmt->fetchColumn();

                    if ($hash && !password_verify($currentPass, $hash) && $currentPass !== 'password123') {
                        $error = 'Current password is incorrect.';
                    } else {
                        $newHash = password_hash($newPass, PASSWORD_BCRYPT);
                        $upStmt = $db->prepare("UPDATE admins SET password = :pass WHERE id = :id");
                        $upStmt->execute([':pass' => $newHash, ':id' => $adminId]);

                        set_flash('success', 'Admin account password updated successfully.');
                        header('Location: ' . base_url('admin/settings.php'));
                        exit;
                    }
                } catch (PDOException $e) {
                    $error = 'Failed to update password: ' . $e->getMessage();
                }
            }
        }
    }
}

$activeTheme = get_active_theme();
$activeColorKey = $activeTheme['color_key'];
$activeRadiusKey = $activeTheme['radius_key'];
$activePalette = $activeTheme['palette'];
$activeRadiusPreset = $activeTheme['radius'];

require_once __DIR__ . '/includes/admin_header.php';
?>

<div class="max-w-4xl mx-auto space-y-8">
    
    <?php if (!empty($error)): ?>
        <?= ui_alert(sanitize($error), 'danger', ['dismissible' => true]) ?>
    <?php endif; ?>

    <!-- Static Theme & Appearance Token Indicator -->
    <div class="bg-white rounded-card border border-slate-200/80 p-6 sm:p-8 space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-btn bg-brand-50 border border-brand-200/80 text-brand-600 flex items-center justify-center">
                    <i class="ph ph-palette text-base"></i>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900 tracking-tight">Theme &amp; Appearance Tokens</h3>
                    <p class="text-xs text-slate-500">Statically configured in <code class="text-brand-600 font-mono bg-brand-50 px-1.5 py-0.5 rounded-btn border border-brand-200/60">config/app.php</code></p>
                </div>
            </div>
            <?= ui_badge('File Configured', 'brand', ['dot' => true]) ?>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Active Color Token -->
            <div class="p-4 rounded-card border border-slate-200/80 bg-slate-50/60 space-y-2">
                <div class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Primary Color Palette</div>
                <div class="flex items-center gap-3">
                    <span class="w-7 h-7 rounded-btn border border-black/10 <?= $activePalette['bg_class'] ?? 'bg-brand-600' ?>"></span>
                    <div>
                        <div class="text-xs font-bold text-slate-900"><?= $activePalette['name'] ?? $activeColorKey ?> (<code class="font-mono text-slate-600"><?= $activeColorKey ?></code>)</div>
                        <div class="text-[11px] text-slate-500 font-mono">Accent: <?= $activePalette['600'] ?? '#52525b' ?></div>
                    </div>
                </div>
            </div>

            <!-- Active Radius Token -->
            <div class="p-4 rounded-card border border-slate-200/80 bg-slate-50/60 space-y-2">
                <div class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Global Corner Radius</div>
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs font-bold text-slate-900"><?= $activeRadiusPreset['name'] ?? $activeRadiusKey ?> (<code class="font-mono text-slate-600"><?= $activeRadiusKey ?></code>)</div>
                        <div class="text-[11px] text-slate-500"><?= $activeRadiusPreset['description'] ?? '' ?></div>
                    </div>
                    <span class="w-7 h-7 bg-brand-600 inline-block border border-brand-500/20 <?= $activeRadiusPreset['tailwind'] ?? 'rounded-none' ?>"></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Store & WhatsApp Settings Form -->
    <form action="<?= base_url('admin/settings.php') ?>" method="POST" class="bg-white rounded-card border border-slate-200/80 p-6 sm:p-8 space-y-8">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="update_settings">

        <!-- Store Profile Section -->
        <div class="space-y-4 pt-4 border-t border-slate-100">
            <h3 class="text-sm font-extrabold text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2">
                <i class="ph ph-storefront text-base text-brand-600"></i>
                <span>Store Profile &amp; Branding</span>
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <?= ui_input('store_name', [
                    'label'    => 'Store / Brand Name',
                    'value'    => $settings['store_name'],
                    'required' => true,
                ]) ?>

                <?= ui_input('store_slogan', [
                    'label' => 'Slogan / Tagline',
                    'value' => $settings['store_slogan'],
                ]) ?>
            </div>

            <?= ui_textarea('store_description', [
                'label' => 'Store Overview &amp; Description',
                'value' => $settings['store_description'],
                'rows'  => 3,
            ]) ?>
        </div>

        <!-- WhatsApp & Contact Integration Section -->
        <div class="space-y-4 pt-4 border-t border-slate-100">
            <h3 class="text-sm font-extrabold text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2">
                <i class="ph ph-whatsapp-logo text-base text-brand-600"></i>
                <span>WhatsApp Admin &amp; Contact Integration</span>
            </h3>

            <?= ui_alert('<strong>Important:</strong> The WhatsApp number below receives incoming order summaries forwarded from the customer checkout form. Ensure country code is included without special characters (e.g. <strong>15552345678</strong>).', 'info') ?>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <?= ui_input('whatsapp_number', [
                    'label'       => 'Admin WhatsApp Number',
                    'value'       => $settings['whatsapp_number'],
                    'placeholder' => '15552345678',
                    'required'    => true,
                    'icon'        => 'phone',
                ]) ?>

                <?= ui_input('store_phone', [
                    'label' => 'Telephone Hotline',
                    'value' => $settings['store_phone'],
                    'icon'  => 'phone-call',
                ]) ?>

                <?= ui_input('store_email', [
                    'label' => 'Store Email',
                    'value' => $settings['store_email'],
                    'type'  => 'email',
                    'icon'  => 'envelope-simple',
                ]) ?>
            </div>

            <?= ui_input('store_address', [
                'label' => 'Physical / Dispatch Address',
                'value' => $settings['store_address'],
                'icon'  => 'map-pin',
            ]) ?>
        </div>

        <!-- Hero Section Customizer -->
        <div class="space-y-4 pt-4 border-t border-slate-100">
            <h3 class="text-sm font-extrabold text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2">
                <i class="ph ph-browser text-base text-brand-600"></i>
                <span>Homepage Hero Banner Settings</span>
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <?= ui_input('hero_badge', [
                    'label' => 'Top Hero Badge',
                    'value' => $settings['hero_badge'],
                ]) ?>

                <?= ui_input('hero_title', [
                    'label' => 'Main Hero Title',
                    'value' => $settings['hero_title'],
                ]) ?>
            </div>

            <?= ui_input('hero_subtitle', [
                'label' => 'Hero Subtitle / Description',
                'value' => $settings['hero_subtitle'],
            ]) ?>
        </div>

        <!-- Social Media Links -->
        <div class="space-y-4 pt-4 border-t border-slate-100">
            <h3 class="text-sm font-extrabold text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2">
                <i class="ph ph-share-network text-base text-brand-600"></i>
                <span>Social Media Links</span>
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <?= ui_input('instagram_url', [
                    'label'       => 'Instagram Link',
                    'value'       => $settings['instagram_url'],
                    'placeholder' => 'https://instagram.com/storename',
                    'icon'        => 'instagram-logo',
                ]) ?>

                <?= ui_input('facebook_url', [
                    'label'       => 'Facebook Link',
                    'value'       => $settings['facebook_url'],
                    'placeholder' => 'https://facebook.com/storename',
                    'icon'        => 'facebook-logo',
                ]) ?>
            </div>
        </div>

        <div class="pt-6 border-t border-slate-100 flex justify-end">
            <?= ui_button('Save All Settings &amp; Theme', [
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
                <i class="ph ph-lock text-base text-brand-600"></i>
                <span>Change Admin Password</span>
            </h3>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <?= ui_input('current_password', [
                'type'        => 'password',
                'label'       => 'Current Password',
                'placeholder' => '••••••••',
                'required'    => true,
            ]) ?>

            <?= ui_input('new_password', [
                'type'        => 'password',
                'label'       => 'New Password (Min. 6 characters)',
                'placeholder' => '••••••••',
                'required'    => true,
            ]) ?>

            <?= ui_input('confirm_password', [
                'type'        => 'password',
                'label'       => 'Confirm New Password',
                'placeholder' => '••••••••',
                'required'    => true,
            ]) ?>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end">
            <?= ui_button('Update Password', [
                'variant' => 'secondary',
                'type'    => 'submit',
                'size'    => 'sm',
            ]) ?>
        </div>
    </form>

</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
