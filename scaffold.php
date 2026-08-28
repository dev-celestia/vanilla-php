<?php
/**
 * Native-PHP Feature Installer & App Scaffolder
 * 
 * Runs via CLI (`php scaffold.php`) or Web Browser (`http://localhost:8000/scaffold.php`).
 * Automatically transforms the showcase repository into a clean, production-ready
 * E-Commerce & Admin CMS web application without demo documentation.
 */

require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/database.php';

$isCli = (php_sapi_name() === 'cli');
$action = $_POST['action'] ?? ($isCli ? 'run' : '');
$appName = trim($_POST['app_name'] ?? 'Toko Online Saya');
$removeDocs = isset($_POST['remove_docs']) ? (bool)$_POST['remove_docs'] : true;

function scaffold_log($msg, $isCli, $type = 'info') {
    if ($isCli) {
        $prefix = match($type) {
            'success' => '✅ ',
            'warn'    => '⚠️ ',
            'error'   => '❌ ',
            'step'    => '📦 ',
            default   => '🔹 '
        };
        echo $prefix . strip_tags($msg) . PHP_EOL;
    }
}

function run_scaffolding($baseDir, $isCli, $customAppName = '') {
    $results = [];

    // 1. Promote demo.php to index.php (The actual Storefront)
    $demoFile = $baseDir . '/demo.php';
    $indexFile = $baseDir . '/index.php';
    
    if (file_exists($demoFile)) {
        $demoContent = file_get_contents($demoFile);
        
        // Adjust active nav and title for main index
        $demoContent = str_replace(
            "\$active_nav = 'demo';",
            "\$active_nav = 'home';",
            $demoContent
        );
        $demoContent = str_replace(
            "\$page_title = 'Showcase Demo Store - Native PHP UI';",
            "\$page_title = \$settings['store_name'] . ' - Katalog Produk & Belanja';",
            $demoContent
        );

        file_put_contents($indexFile, $demoContent);
        @unlink($demoFile);
        $results[] = ['type' => 'success', 'msg' => 'Storefront katalog berhasil dijadikan sebagai <code>index.php</code> utama.'];
        scaffold_log('Storefront katalog berhasil dijadikan sebagai index.php utama.', $isCli, 'success');
    } else {
        $results[] = ['type' => 'info', 'msg' => 'File <code>index.php</code> sudah menggunakan etalase toko.'];
        scaffold_log('File index.php sudah menggunakan etalase toko.', $isCli, 'info');
    }

    // 2. Clean includes/header.php navigation for Storefront
    $headerFile = $baseDir . '/includes/header.php';
    if (file_exists($headerFile)) {
        $header = file_get_contents($headerFile);

        // Replace brand title text from "NativePHP UI Design System" to dynamic Store Name
        $brandSearch = '<span class="font-semibold text-sm tracking-tight text-slate-900 block leading-none flex items-center gap-1">
                            NativePHP <span class="px-1.5 py-0.5 rounded bg-brand-50 text-brand-700 text-[10px] font-semibold border border-brand-200/80">UI</span>
                        </span>
                        <span class="text-[10px] text-slate-400 font-normal hidden lg:block leading-none mt-0.5">
                            Design System
                        </span>';
        
        $brandReplace = '<span class="font-semibold text-sm tracking-tight text-slate-900 block leading-none">
                            <?= sanitize($settings[\'store_name\'] ?? \'Native Shop\') ?>
                        </span>
                        <span class="text-[10px] text-slate-400 font-normal hidden lg:block leading-none mt-0.5">
                            <?= sanitize($settings[\'store_slogan\'] ?? \'Official Online Store\') ?>
                        </span>';

        if (str_contains($header, 'NativePHP UI')) {
            $header = str_replace($brandSearch, $brandReplace, $header);
        }

        // Clean Desktop Navigation Links
        $cleanNav = '<!-- Desktop Nav Links -->
                <nav class="hidden md:flex items-center space-x-1">
                    <a href="<?= base_url() ?>" class="px-3 py-1.5 text-[13px] font-medium rounded-btn transition-colors apple-tap <?= !isset($active_nav) || $active_nav === \'home\' ? \'text-brand-700 bg-brand-50/80 border border-brand-200/60 font-semibold\' : \'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 border border-transparent\' ?>">
                        <i class="ph ph-storefront mr-1"></i> Katalog
                    </a>
                    <a href="<?= base_url(\'cart.php\') ?>" class="px-3 py-1.5 text-[13px] font-medium rounded-btn transition-colors apple-tap <?= isset($active_nav) && $active_nav === \'cart\' ? \'text-brand-700 bg-brand-50/80 border border-brand-200/60 font-semibold\' : \'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 border border-transparent\' ?>">
                        <i class="ph ph-shopping-bag mr-1"></i> Keranjang
                    </a>
                    <a href="<?= base_url(\'about.php\') ?>" class="px-3 py-1.5 text-[13px] font-medium rounded-btn transition-colors apple-tap <?= isset($active_nav) && $active_nav === \'about\' ? \'text-brand-700 bg-brand-50/80 border border-brand-200/60 font-semibold\' : \'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 border border-transparent\' ?>">
                        Tentang Kami
                    </a>
                    <a href="<?= base_url(\'contact.php\') ?>" class="px-3 py-1.5 text-[13px] font-medium rounded-btn transition-colors apple-tap <?= isset($active_nav) && $active_nav === \'contact\' ? \'text-brand-700 bg-brand-50/80 border border-brand-200/60 font-semibold\' : \'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 border border-transparent\' ?>">
                        Hubungi Kami
                    </a>
                </nav>';

        $header = preg_replace(
            '/<!-- Desktop Nav Links.*?<\/nav>/s',
            $cleanNav,
            $header
        );

        // Always enable cart & search buttons across store pages
        $header = str_replace(
            '$is_demo_page = (isset($active_nav) && $active_nav === \'demo\') || in_array(basename($_SERVER[\'PHP_SELF\'] ?? \'\'), [\'demo.php\', \'product.php\', \'cart.php\', \'checkout.php\', \'order-success.php\']);',
            '$is_demo_page = true; // Storefront mode enabled',
            $header
        );

        file_put_contents($headerFile, $header);
        $results[] = ['type' => 'success', 'msg' => 'Navigasi <code>includes/header.php</code> dibersihkan untuk toko online.'];
        scaffold_log('Navigasi includes/header.php dibersihkan untuk toko online.', $isCli, 'success');
    }

    // 3. Clean includes/footer.php
    $footerFile = $baseDir . '/includes/footer.php';
    if (file_exists($footerFile)) {
        $footer = file_get_contents($footerFile);
        $footer = str_replace(
            '<li><a href="<?= base_url() ?>" class="hover:text-brand-300 transition flex items-center gap-1.5"><i class="ph ph-caret-right text-brand-400 text-xs"></i> Overview & Architecture</a></li>',
            '<li><a href="<?= base_url() ?>" class="hover:text-brand-300 transition flex items-center gap-1.5"><i class="ph ph-caret-right text-brand-400 text-xs"></i> Katalog Produk</a></li>',
            $footer
        );
        $footer = str_replace(
            '<li><a href="<?= base_url(\'design-system.php\') ?>" class="hover:text-brand-300 transition flex items-center gap-1.5"><i class="ph ph-caret-right text-brand-400 text-xs"></i> Design System & Tokens</a></li>',
            '<li><a href="<?= base_url(\'cart.php\') ?>" class="hover:text-brand-300 transition flex items-center gap-1.5"><i class="ph ph-caret-right text-brand-400 text-xs"></i> Keranjang Belanja</a></li>',
            $footer
        );
        $footer = str_replace(
            '<li><a href="<?= base_url(\'components.php\') ?>" class="hover:text-brand-300 transition flex items-center gap-1.5"><i class="ph ph-caret-right text-brand-400 text-xs"></i> Component Primitives</a></li>',
            '<li><a href="<?= base_url(\'checkout.php\') ?>" class="hover:text-brand-300 transition flex items-center gap-1.5"><i class="ph ph-caret-right text-brand-400 text-xs"></i> Checkout Order</a></li>',
            $footer
        );
        $footer = str_replace(
            '<li><a href="<?= base_url(\'demo.php\') ?>" class="hover:text-brand-300 transition flex items-center gap-1.5"><i class="ph ph-caret-right text-brand-400 text-xs"></i> E-Commerce Demo Store</a></li>',
            '<li><a href="<?= base_url(\'admin/orders.php\') ?>" class="hover:text-brand-300 transition flex items-center gap-1.5"><i class="ph ph-caret-right text-brand-400 text-xs"></i> Lacak Pesanan</a></li>',
            $footer
        );
        file_put_contents($footerFile, $footer);
        $results[] = ['type' => 'success', 'msg' => 'Footer <code>includes/footer.php</code> disesuaikan ke menu toko.'];
        scaffold_log('Footer includes/footer.php disesuaikan ke menu toko.', $isCli, 'success');
    }

    // 4. Remove Showcase & Docs Files
    $docsToRemove = [
        $baseDir . '/components.php',
        $baseDir . '/design-system.php',
        $baseDir . '/THEME_GUIDE.md',
    ];

    foreach ($docsToRemove as $file) {
        if (file_exists($file)) {
            @unlink($file);
            $results[] = ['type' => 'success', 'msg' => 'Hapus file showcase: <code>' . basename($file) . '</code>'];
            scaffold_log('Hapus file showcase: ' . basename($file), $isCli, 'success');
        }
    }

    // 5. Database Auto-Setup (Initialize Schema & Default Seeds)
    $dbInitFile = $baseDir . '/database/init.php';
    if (file_exists($dbInitFile)) {
        scaffold_log('Menjalankan Database Auto-Setup...', $isCli, 'step');
        
        // Execute database setup logic directly
        try {
            $pdoRoot = new PDO(
                sprintf('mysql:host=%s;port=%s;charset=utf8mb4', DB_HOST, DB_PORT),
                DB_USER,
                DB_PASS,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            $pdoRoot->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
            
            $db = getDB();
            if ($db) {
                $sqlFile = $baseDir . '/database/schema.sql';
                if (file_exists($sqlFile)) {
                    $sql = file_get_contents($sqlFile);
                    $db->exec($sql);

                    // If custom store name is provided, update settings table
                    if (!empty($customAppName)) {
                        $stmt = $db->prepare("UPDATE settings SET value = :val WHERE `key` = 'store_name'");
                        $stmt->execute([':val' => $customAppName]);
                    }

                    $results[] = ['type' => 'success', 'msg' => 'Database <strong>' . DB_NAME . '</strong> & seluruh tabel berhasil dimigrasi dan di-seed!'];
                    scaffold_log('Database ' . DB_NAME . ' & seluruh tabel berhasil dimigrasi dan di-seed!', $isCli, 'success');
                }
            }
        } catch (PDOException $e) {
            $results[] = ['type' => 'warn', 'msg' => 'Catatan Database: ' . htmlspecialchars($e->getMessage())];
            scaffold_log('Catatan Database: ' . $e->getMessage(), $isCli, 'warn');
        }
    }

    return $results;
}

// ----------------------------------------------------
// CLI Execution
// ----------------------------------------------------
if ($isCli) {
    echo "\n========================================================\n";
    echo "⚡ Native PHP - Feature Installer & Scaffolder\n";
    echo "========================================================\n\n";
    
    $results = run_scaffolding(__DIR__, true);
    
    echo "\n--------------------------------------------------------\n";
    echo "🎉 Scaffolding Selesai! Aplikasi Toko & Admin CMS Siap.\n";
    echo "--------------------------------------------------------\n";
    echo "🔑 Kredensial Admin Default:\n";
    echo "   URL:      http://localhost:8000/admin/login.php\n";
    echo "   Username: admin\n";
    echo "   Password: password123\n\n";
    echo "🚀 Jalankan: npm run dev:php  (atau php -S 0.0.0.0:8000)\n\n";
    exit(0);
}

// ----------------------------------------------------
// Web Browser UI Execution
// ----------------------------------------------------
$hasRun = false;
$results = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'scaffold') {
    $results = run_scaffolding(__DIR__, false, $appName);
    $hasRun = true;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feature Installer & Scaffolder - Native PHP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/regular/style.css">
    <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/bold/style.css">
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex items-center justify-center p-4 antialiased selection:bg-emerald-500 selection:text-white">

    <div class="max-w-xl w-full bg-slate-950 border border-slate-800 rounded-3xl p-8 sm:p-10 shadow-2xl relative overflow-hidden">
        
        <!-- Glow accents -->
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Header -->
        <div class="flex items-center gap-3.5 mb-6">
            <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center text-2xl font-bold">
                <i class="ph-bold ph-package"></i>
            </div>
            <div>
                <h1 class="text-xl font-bold text-white tracking-tight">Feature Installer & Scaffolder</h1>
                <p class="text-xs text-slate-400">Scaffold Toko Online & Admin CMS Bersih (Zero Demo Bloat)</p>
            </div>
        </div>

        <?php if ($hasRun): ?>
            <!-- Results View -->
            <div class="space-y-3 mb-6 bg-slate-900/80 rounded-2xl p-5 border border-slate-800 text-xs">
                <p class="font-semibold text-slate-200 text-sm mb-2 flex items-center gap-2">
                    <i class="ph ph-check-circle text-emerald-400 text-base"></i> Proses Scaffolding Berhasil:
                </p>
                <?php foreach ($results as $res): ?>
                    <div class="flex items-start gap-2 text-slate-300">
                        <span><?= $res['type'] === 'success' ? '✅' : ($res['type'] === 'warn' ? '⚠️' : '🔹') ?></span>
                        <div><?= $res['msg'] ?></div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="p-4 bg-emerald-950/40 border border-emerald-500/30 rounded-2xl mb-6 text-xs text-emerald-300">
                <p class="font-semibold mb-1">🔑 Akses Panel Admin Default:</p>
                <p>Username: <code class="bg-emerald-900/60 px-1.5 py-0.5 rounded text-white">admin</code> | Password: <code class="bg-emerald-900/60 px-1.5 py-0.5 rounded text-white">password123</code></p>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <a href="<?= base_url() ?>" class="flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-medium text-xs transition">
                    <i class="ph ph-storefront text-base"></i> Buka Toko Online
                </a>
                <a href="<?= base_url('admin/login.php') ?>" class="flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-medium text-xs border border-slate-700 transition">
                    <i class="ph ph-shield-check text-base"></i> Masuk Admin
                </a>
            </div>

        <?php else: ?>
            <!-- Initial Form -->
            <form method="POST" action="scaffold.php" class="space-y-5">
                <input type="hidden" name="action" value="scaffold">

                <div class="p-4 bg-slate-900/60 border border-slate-800 rounded-2xl space-y-2 text-xs text-slate-300">
                    <p class="font-medium text-white flex items-center gap-1.5">
                        <i class="ph ph-info text-emerald-400"></i> Apa yang akan dilakukan oleh Scaffolder ini?
                    </p>
                    <ul class="space-y-1.5 text-slate-400 pl-4 list-disc">
                        <li>Mengubah <code>demo.php</code> menjadi halaman toko utama <code>index.php</code>.</li>
                        <li>Membersihkan navigasi dari tautan dokumentasi & living styleguide.</li>
                        <li>Menghapus file showcase (<code>design-system.php</code>, <code>components.php</code>).</li>
                        <li>Menginisialisasi skema database MySQL & data produk awal.</li>
                    </ul>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-slate-300">Nama Toko / Bisnis Anda</label>
                    <input type="text" name="app_name" value="Toko Online Saya" class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-800 text-white text-xs focus:outline-none focus:border-emerald-500 transition" required>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs tracking-tight transition flex items-center justify-center gap-2 shadow-lg shadow-emerald-950">
                        <i class="ph-bold ph-lightning text-base"></i> Pasang Fitur Sekarang (Scaffold Clean App)
                    </button>
                </div>
            </form>
        <?php endif; ?>

        <div class="mt-8 pt-5 border-t border-slate-800/80 text-center text-[11px] text-slate-500">
            Native PHP UI Framework &bull; Zero Bloat &bull; Production Ready
        </div>

    </div>

</body>
</html>
