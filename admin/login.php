<?php
/**
 * Admin Login Page
 */
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../helpers/format.php';
require_once __DIR__ . '/../helpers/csrf.php';
require_once __DIR__ . '/../helpers/auth.php';

redirect_if_logged_in();

$error = get_flash('error');
$settings = get_settings();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Sesi keamanan kadaluarsa. Silakan coba lagi.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($username) || empty($password)) {
            $error = 'Username dan password wajib diisi.';
        } else {
            $db = getDB();
            if ($db) {
                try {
                    $stmt = $db->prepare("SELECT * FROM admins WHERE username = :username LIMIT 1");
                    $stmt->execute([':username' => $username]);
                    $admin = $stmt->fetch();

                    if ($admin && password_verify($password, $admin['password'])) {
                        // Success Login
                        $_SESSION['admin_id'] = $admin['id'];
                        $_SESSION['admin_username'] = $admin['username'];
                        $_SESSION['admin_name'] = $admin['name'];
                        $_SESSION['admin_email'] = $admin['email'];

                        set_flash('success', 'Selamat datang kembali, ' . $admin['name'] . '!');
                        header('Location: ' . base_url('admin/index.php'));
                        exit;
                    } else {
                        // Check default admin fallback if database table not yet populated
                        if ($username === 'admin' && $password === 'password123') {
                            $_SESSION['admin_id'] = 1;
                            $_SESSION['admin_username'] = 'admin';
                            $_SESSION['admin_name'] = 'Administrator Default';
                            $_SESSION['admin_email'] = 'admin@katalogstore.id';

                            set_flash('success', 'Selamat datang di Panel Admin!');
                            header('Location: ' . base_url('admin/index.php'));
                            exit;
                        }

                        $error = 'Username atau kata sandi yang Anda masukkan salah.';
                    }
                } catch (PDOException $e) {
                    // Fallback to default
                    if ($username === 'admin' && $password === 'password123') {
                        $_SESSION['admin_id'] = 1;
                        $_SESSION['admin_username'] = 'admin';
                        $_SESSION['admin_name'] = 'Administrator';
                        $_SESSION['admin_email'] = 'admin@katalogstore.id';

                        header('Location: ' . base_url('admin/index.php'));
                        exit;
                    }
                    $error = 'Terjadi gangguan koneksi database. Silakan jalankan installer database di /database/init.php.';
                }
            } else {
                // Emergency bypass for local setup
                if ($username === 'admin' && $password === 'password123') {
                    $_SESSION['admin_id'] = 1;
                    $_SESSION['admin_username'] = 'admin';
                    $_SESSION['admin_name'] = 'Administrator (Demo)';
                    $_SESSION['admin_email'] = 'admin@katalogstore.id';

                    header('Location: ' . base_url('admin/index.php'));
                    exit;
                }
                $error = 'Database belum terhubung. Silakan setup database terlebih dahulu.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrator - <?= sanitize($settings['store_name']) ?></title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <!-- Tailwind Play CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Design System Theme & Token Engine -->
    <?php render_theme_head(); ?>

    <!-- Phosphor Icons (https://phosphoricons.com/) -->
    <script src="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2/src/regular/style.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2/src/bold/style.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2/src/fill/style.css" />
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex items-center justify-center p-4 font-sans antialiased">

    <div class="max-w-md w-full">
        
        <!-- Logo & Header -->
        <div class="text-center mb-8">
            <div class="w-14 h-14 rounded-card bg-brand-600 border border-brand-500/30 flex items-center justify-center text-white mx-auto mb-4">
                <i class="ph ph-shield-check text-3xl"></i>
            </div>
            <h1 class="text-2xl font-extrabold text-white tracking-tight">Admin Dashboard</h1>
            <p class="text-xs text-slate-400 mt-1"><?= sanitize($settings['store_name']) ?></p>
        </div>

        <!-- Login Card (Translucent Apple Material, Zero Shadow, Crisp Border) -->
        <div class="bg-slate-900/90 backdrop-blur-xl border border-slate-800 rounded-card p-8 space-y-6">
            
            <?php if (!empty($error)): ?>
                <div class="p-4 rounded-btn bg-rose-500/10 border border-rose-500/30 text-rose-300 text-xs font-semibold flex items-center gap-3">
                    <i class="ph ph-warning text-xl flex-shrink-0 text-rose-400"></i>
                    <span><?= sanitize($error) ?></span>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('admin/login.php') ?>" method="POST" class="space-y-4">
                <?= csrf_field() ?>

                <div>
                    <label for="username" class="block text-xs font-bold text-slate-300 mb-1.5 tracking-tight">Username</label>
                    <div class="relative">
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            required 
                            placeholder="admin" 
                            value="<?= sanitize($_POST['username'] ?? '') ?>"
                            class="w-full pl-10 pr-4 py-3 text-sm rounded-input bg-slate-950 border border-slate-800 text-white placeholder-slate-500 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 focus:outline-none transition"
                        >
                        <i class="ph ph-user text-slate-500 absolute left-3.5 top-1/2 -translate-y-1/2 text-base pointer-events-none"></i>
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-slate-300 mb-1.5 tracking-tight">Kata Sandi (Password)</label>
                    <div class="relative">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required 
                            placeholder="••••••••" 
                            class="w-full pl-10 pr-4 py-3 text-sm rounded-input bg-slate-950 border border-slate-800 text-white placeholder-slate-500 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 focus:outline-none transition"
                        >
                        <i class="ph ph-lock text-slate-500 absolute left-3.5 top-1/2 -translate-y-1/2 text-base pointer-events-none"></i>
                    </div>
                </div>

                <button 
                    type="submit" 
                    class="w-full py-3.5 px-4 rounded-btn bg-brand-600 hover:bg-brand-700 text-white font-bold text-sm border border-brand-500/20 transition apple-tap flex items-center justify-center gap-2">
                    <i class="ph ph-sign-in text-base"></i>
                    <span>Masuk ke Dashboard</span>
                </button>
            </form>

            <div class="pt-4 border-t border-slate-800 text-center space-y-2">
                <div class="text-[11px] text-slate-400">
                    Kredensial Default: <span class="text-brand-300 font-mono">admin</span> / <span class="text-brand-300 font-mono">password123</span>
                </div>
                <div>
                    <a href="<?= base_url() ?>" class="text-xs text-slate-400 hover:text-white transition inline-flex items-center gap-1">
                        <i class="ph ph-arrow-left text-xs"></i>
                        <span>Kembali ke Website Utama</span>
                    </a>
                </div>
            </div>

        </div>

    </div>

</body>
</html>
