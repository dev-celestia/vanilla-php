<?php
/**
 * Modular Feature Installer & Scaffolder Engine
 * 
 * Configured via: `scaffolder/manifest.json`
 * Runs via CLI (`php scaffolder/index.php [target]`) or Web Browser (`http://localhost:8000/scaffolder/`).
 */

$rootDir = dirname(__DIR__);

if (file_exists($rootDir . '/config/app.php')) {
    require_once $rootDir . '/config/app.php';
}
if (file_exists($rootDir . '/config/database.php')) {
    require_once $rootDir . '/config/database.php';
}

$manifestFile = __DIR__ . '/manifest.json';
$manifest = file_exists($manifestFile) ? json_decode(file_get_contents($manifestFile), true) : [];

$isCli = (php_sapi_name() === 'cli');

/**
 * Format folder name / path into a readable store title
 */
function format_folder_name_to_title($path, $fallback = 'My Online Store', $rootDir = null) {
    if (empty($path) || $path === '.' || $path === './') {
        $raw = basename($rootDir ?: dirname(__DIR__));
    } else {
        $raw = basename(rtrim($path, '/\\'));
    }
    
    $clean = trim(preg_replace('/[_\-]+/', ' ', $raw));
    if (!empty($clean)) {
        return ucwords(strtolower($clean));
    }
    return $fallback;
}

// Parse CLI Arguments
$cliTargetDir = '';
$cliAppName = null;
$cliSkipDb = false;
$cliHelp = false;
$cliInPlace = false;

if ($isCli) {
    global $argv;
    for ($i = 1; $i < count($argv); $i++) {
        $arg = $argv[$i];
        if (str_starts_with($arg, '--target=')) {
            $cliTargetDir = substr($arg, 9);
        } elseif (str_starts_with($arg, '--name=')) {
            $cliAppName = substr($arg, 7);
        } elseif ($arg === '--no-db') {
            $cliSkipDb = true;
        } elseif ($arg === '--in-place') {
            $cliInPlace = true;
        } elseif ($arg === '--help' || $arg === '-h') {
            $cliHelp = true;
        } elseif (!str_starts_with($arg, '-')) {
            $cliTargetDir = $arg;
        }
    }
}

if (empty($cliAppName)) {
    $cliAppName = format_folder_name_to_title($cliTargetDir, 'My Online Store', $rootDir);
}

$action = $_POST['action'] ?? ($isCli ? 'run' : '');
$targetPathInput = trim($_POST['target_path'] ?? ($isCli ? ($cliTargetDir ?: '') : ''));
$appName = trim($_POST['app_name'] ?? ($isCli ? $cliAppName : format_folder_name_to_title($targetPathInput ?: '.', 'My Online Store', $rootDir)));

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

/**
 * Recursive directory copy with exclusion filters
 */
function copy_recursive($src, $dst, $exclude = []) {
    $dir = opendir($src);
    @mkdir($dst, 0755, true);
    while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            if (in_array($file, $exclude)) {
                continue;
            }
            $srcPath = $src . '/' . $file;
            $dstPath = $dst . '/' . $file;
            if (is_dir($srcPath)) {
                copy_recursive($srcPath, $dstPath, $exclude);
            } else {
                copy($srcPath, $dstPath);
            }
        }
    }
    closedir($dir);
}

function run_scaffolding($sourceDir, $rawTargetDir, $isCli, $customAppName = '', $skipDb = false, $manifest = []) {
    $results = [];

    // Resolve target path
    if (empty($rawTargetDir) || $rawTargetDir === '.' || $rawTargetDir === './') {
        $targetDir = $sourceDir;
        $isExternalTarget = false;
    } else {
        if ($rawTargetDir[0] === '/' || (strlen($rawTargetDir) > 2 && $rawTargetDir[1] === ':')) {
            $targetDir = rtrim($rawTargetDir, '/\\');
        } else {
            $targetDir = rtrim(realpath($sourceDir) . '/' . $rawTargetDir, '/\\');
        }
        $isExternalTarget = (realpath($sourceDir) !== realpath($targetDir));
    }

    scaffold_log("Lokasi Tujuan: " . $targetDir, $isCli, 'step');

    if ($isExternalTarget) {
        // ----------------------------------------------------
        // Case A: Scaffolding into a NEW external target directory
        // ----------------------------------------------------
        if (!is_dir($targetDir)) {
            if (!@mkdir($targetDir, 0755, true)) {
                $results[] = ['type' => 'error', 'msg' => 'Gagal membuat direktori tujuan: ' . $targetDir];
                scaffold_log('Gagal membuat direktori tujuan: ' . $targetDir, $isCli, 'error');
                return ['targetDir' => $targetDir, 'isExternal' => true, 'results' => $results];
            }
        }

        $results[] = ['type' => 'success', 'msg' => 'Direktori tujuan disiapkan: <code>' . htmlspecialchars($targetDir) . '</code>'];

        // Copy directories defined in manifest
        $directories = $manifest['directories'] ?? ['config', 'helpers', 'components', 'ui', 'includes', 'admin', 'database', 'uploads', 'dist', 'resources', 'scaffolder'];
        foreach ($directories as $folder) {
            $srcFolder = $sourceDir . '/' . $folder;
            $dstFolder = $targetDir . '/' . $folder;
            if (is_dir($srcFolder)) {
                copy_recursive($srcFolder, $dstFolder, $manifest['ignore'] ?? ['.git', 'node_modules']);
            }
        }

        // Copy files defined in manifest
        $files = $manifest['files'] ?? ['cart.php', 'checkout.php', 'contact.php', 'order-success.php', 'product.php', 'scaffold.php', '.htaccess', '.env.example', 'package.json', 'vite.config.js'];
        foreach ($files as $file) {
            $srcFile = $sourceDir . '/' . $file;
            $dstFile = $targetDir . '/' . $file;
            if (file_exists($srcFile)) {
                copy($srcFile, $dstFile);
            }
        }

        // Convert entrypoint transform (demo.php -> index.php in target folder)
        $transform = $manifest['entrypoint_transform'] ?? ['source' => 'demo.php', 'destination' => 'index.php'];
        $srcTransform = $sourceDir . '/' . ($transform['source'] ?? 'demo.php');
        $dstTransform = $targetDir . '/' . ($transform['destination'] ?? 'index.php');

        if (file_exists($srcTransform)) {
            $demoContent = file_get_contents($srcTransform);
            $demoContent = str_replace("\$active_nav = 'demo';", "\$active_nav = 'home';", $demoContent);
            $demoContent = str_replace("\$page_title = 'Showcase Demo Store - Vanilla PHP UI';", "\$page_title = \$settings['store_name'] . ' - Katalog Produk';", $demoContent);
            $demoContent = str_replace("\$page_title = 'Showcase Demo Store - Native PHP UI';", "\$page_title = \$settings['store_name'] . ' - Katalog Produk';", $demoContent);
            file_put_contents($dstTransform, $demoContent);
        }

        $results[] = ['type' => 'success', 'msg' => 'Halaman etalase toko (demo.php) berhasil disalin sebagai <code>index.php</code> di folder tujuan.'];
        scaffold_log('Halaman etalase toko berhasil disalin sebagai index.php utama di folder tujuan.', $isCli, 'success');

    } else {
        // ----------------------------------------------------
        // Case B: Same Directory (Safety Guard - Do NOT modify)
        // ----------------------------------------------------
        $results[] = ['type' => 'warn', 'msg' => 'Scaffolder dirancang khusus untuk membuat salinan toko ke direktori lain (misal: <code>../toko-baru</code>). Proyek template saat ini tetap aman dan tidak diubah.'];
        scaffold_log('Scaffolder hanya menyalin ke direktori lain. Direktori template asli dipertahankan tanpa perubahan.', $isCli, 'warn');
        return [
            'targetDir' => $targetDir,
            'isExternal' => false,
            'results' => $results
        ];
    }

    // ----------------------------------------------------
    // Update Header & Footer in Target for Clean Storefront
    // ----------------------------------------------------
    $headerFile = $targetDir . '/includes/header.php';
    if (file_exists($headerFile)) {
        $header = file_get_contents($headerFile);

        $brandSearch = '<span class="font-semibold text-sm tracking-tight text-slate-900 block leading-none flex items-center gap-1">
                            VanillaPHP <span class="px-1.5 py-0.5 rounded bg-brand-50 text-brand-700 text-[10px] font-semibold border border-brand-200/80">UI</span>
                        </span>
                        <span class="text-[10px] text-slate-400 font-normal hidden lg:block leading-none mt-0.5">
                            Design System
                        </span>';
        $brandSearchOld = '<span class="font-semibold text-sm tracking-tight text-slate-900 block leading-none flex items-center gap-1">
                            NativePHP <span class="px-1.5 py-0.5 rounded bg-brand-50 text-brand-700 text-[10px] font-semibold border border-brand-200/80">UI</span>
                        </span>
                        <span class="text-[10px] text-slate-400 font-normal hidden lg:block leading-none mt-0.5">
                            Design System
                        </span>';
        
        $brandReplace = '<span class="font-semibold text-sm tracking-tight text-slate-900 block leading-none">
                            <?= sanitize($settings[\'store_name\'] ?? \'Vanilla Shop\') ?>
                        </span>
                        <span class="text-[10px] text-slate-400 font-normal hidden lg:block leading-none mt-0.5">
                            <?= sanitize($settings[\'store_slogan\'] ?? \'Official Online Store\') ?>
                        </span>';

        if (str_contains($header, 'VanillaPHP UI') || str_contains($header, 'NativePHP UI')) {
            $header = str_replace($brandSearch, $brandReplace, $header);
            $header = str_replace($brandSearchOld, $brandReplace, $header);
        }

        // Clean Desktop Navigation Links
        $cleanNav = '<!-- Desktop Nav Links -->
                <nav class="hidden md:flex items-center space-x-1">
                    <a href="<?= base_url() ?>" class="px-3 py-1.5 text-[13px] font-medium rounded-btn transition-colors apple-tap <?= !isset($active_nav) || $active_nav === \'home\' ? \'text-brand-700 bg-brand-50/80 border border-brand-200/60 font-semibold\' : \'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 border border-transparent\' ?>">
                        <i class="ph ph-storefront mr-1"></i> Catalog
                    </a>
                    <a href="<?= base_url(\'cart.php\') ?>" class="px-3 py-1.5 text-[13px] font-medium rounded-btn transition-colors apple-tap <?= isset($active_nav) && $active_nav === \'cart\' ? \'text-brand-700 bg-brand-50/80 border border-brand-200/60 font-semibold\' : \'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 border border-transparent\' ?>">
                        <i class="ph ph-shopping-bag mr-1"></i> Cart
                    </a>
                    <a href="<?= base_url(\'contact.php\') ?>" class="px-3 py-1.5 text-[13px] font-medium rounded-btn transition-colors apple-tap <?= isset($active_nav) && $active_nav === \'contact\' ? \'text-brand-700 bg-brand-50/80 border border-brand-200/60 font-semibold\' : \'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 border border-transparent\' ?>">
                        Contact Us
                    </a>
                </nav>';

        $header = preg_replace('/<!-- Desktop Nav Links.*?<\/nav>/s', $cleanNav, $header);
        $header = str_replace(
            '$is_demo_page = (isset($active_nav) && in_array($active_nav, [\'demo\', \'cart\', \'checkout\', \'contact\'])) || in_array(basename($_SERVER[\'PHP_SELF\'] ?? \'\'), [\'demo.php\', \'product.php\', \'cart.php\', \'checkout.php\', \'order-success.php\', \'contact.php\']);',
            '$is_demo_page = true;',
            $header
        );

        file_put_contents($headerFile, $header);
        $results[] = ['type' => 'success', 'msg' => 'Navigation in <code>includes/header.php</code> configured for online store.'];
        scaffold_log('Navigation includes/header.php customized.', $isCli, 'success');
    }

    $footerFile = $targetDir . '/includes/footer.php';
    if (file_exists($footerFile)) {
        $footer = file_get_contents($footerFile);
        $footer = str_replace('<?= base_url(\'design-system.php\') ?>', '<?= base_url(\'cart.php\') ?>', $footer);
        $footer = str_replace('Token Explorer', 'Shopping Cart', $footer);
        $footer = str_replace('Component Primitives', 'Order Checkout', $footer);
        $footer = str_replace('Overview & Architecture', 'Product Catalog', $footer);
        file_put_contents($footerFile, $footer);
        $results[] = ['type' => 'success', 'msg' => 'Footer in <code>includes/footer.php</code> configured.'];
    }

    // ----------------------------------------------------
    // Auto-generate .env Configuration File
    // ----------------------------------------------------
    $targetEnv = $targetDir . '/.env';
    if (!file_exists($targetEnv)) {
        $dbNameSlug = strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '_', $customAppName ?: 'vanilla_shop'), '_'));
        if (empty($dbNameSlug)) $dbNameSlug = 'vanilla_shop';

        $envContent = "# Generated by Vanilla-PHP Scaffolder on " . date('Y-m-d H:i:s') . "\n";
        $envContent .= "APP_NAME=\"" . addcslashes($customAppName ?: 'Vanilla Shop', '"') . "\"\n";
        $envContent .= "APP_ENV=local\n";
        $envContent .= "APP_DEBUG=true\n";
        $envContent .= "APP_URL=http://localhost:8000\n\n";
        $envContent .= "# Database Configuration\n";
        $envContent .= "DB_HOST=" . (defined('DB_HOST') ? DB_HOST : '127.0.0.1') . "\n";
        $envContent .= "DB_PORT=" . (defined('DB_PORT') ? DB_PORT : '3306') . "\n";
        $envContent .= "DB_NAME=" . (defined('DB_NAME') ? DB_NAME : $dbNameSlug) . "\n";
        $envContent .= "DB_USER=" . (defined('DB_USER') ? DB_USER : 'root') . "\n";
        $envContent .= "DB_PASS=" . (defined('DB_PASS') ? DB_PASS : '') . "\n\n";
        $envContent .= "# WhatsApp Store Configuration\n";
        $envContent .= "STORE_WHATSAPP=15552345678\n";

        file_put_contents($targetEnv, $envContent);
        $results[] = ['type' => 'success', 'msg' => 'Configuration file <code>.env</code> generated automatically.'];
        scaffold_log('.env configuration created.', $isCli, 'success');
    }

    // ----------------------------------------------------
    // Database Auto-Setup (MySQL schema & seed)
    // ----------------------------------------------------
    if (!$skipDb && defined('DB_HOST')) {
        $sqlFile = $targetDir . '/database/schema.sql';
        if (file_exists($sqlFile)) {
            scaffold_log('Running Database Initialization...', $isCli, 'step');
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
                    $sql = file_get_contents($sqlFile);
                    $db->exec($sql);

                    if (!empty($customAppName)) {
                        $stmt = $db->prepare("UPDATE settings SET setting_value = :val WHERE setting_key = 'store_name'");
                        $stmt->execute([':val' => $customAppName]);
                    }

                    $results[] = ['type' => 'success', 'msg' => 'Database <strong>' . DB_NAME . '</strong> & tables initialized!'];
                    scaffold_log('Database ' . DB_NAME . ' & tables initialized!', $isCli, 'success');
                }
            } catch (PDOException $e) {
                $results[] = ['type' => 'warn', 'msg' => 'Database Note: ' . htmlspecialchars($e->getMessage())];
                scaffold_log('Database Note: ' . $e->getMessage(), $isCli, 'warn');
            }
        }
    }

    return [
        'targetDir' => $targetDir,
        'isExternal' => $isExternalTarget,
        'results' => $results
    ];
}

// ----------------------------------------------------
// CLI Mode Execution
// ----------------------------------------------------
if ($isCli) {
    echo "\n========================================================\n";
    echo "⚡ Vanilla PHP - " . ($manifest['name'] ?? 'Feature Installer & Scaffolder') . "\n";
    echo "========================================================\n\n";

    // If no target directory was specified or --help was requested
    if (empty($cliTargetDir) || $cliHelp) {
        echo "ℹ️  USAGE:\n";
        echo "   php scaffold.php <target-folder> [options]\n\n";
        echo "📌 EXAMPLES:\n";
        echo "   php scaffold.php ../my-collection --name=\"Fashion Collection\"\n";
        echo "   php scaffold.php /var/www/my-store --name=\"My Store\"\n";
        echo "   php scaffold.php ../new-project --no-db\n\n";
        echo "⚙️  OPTIONS:\n";
        echo "   --name=\"Store Name\"   Define store/brand name (default: from folder name)\n";
        echo "   --no-db              Skip MySQL database schema creation\n";
        echo "   --in-place           Run in the current directory without deleting templates\n";
        echo "   --help, -h           Show this help manual\n\n";
        echo "🌐 Or open the Web GUI Installer in your browser:\n";
        echo "   http://localhost:8000/scaffold.php\n\n";
        exit(0);
    }

    $output = run_scaffolding($rootDir, $cliTargetDir, true, $cliAppName, $cliSkipDb, $manifest);

    echo "\n--------------------------------------------------------\n";
    echo "🎉 Scaffolding & Setup Completed Successfully!\n";
    echo "📍 App Location: " . $output['targetDir'] . "\n";
    echo "--------------------------------------------------------\n";
    echo "🔑 Default Admin Credentials:\n";
    echo "   Username: admin\n";
    echo "   Password: password123\n";
    echo "   Login:    http://localhost:8000/admin/login.php\n\n";
    echo "🛠️ Development Next Steps (Extend Features):\n";
    if ($output['isExternal']) {
        echo "   1. Navigate to project:      cd " . $output['targetDir'] . "\n";
    }
    echo "   2. Install node dependencies: pnpm install (or npm install)\n";
    echo "   3. Run Dev Server:            pnpm dev (Vite + Tailwind HMR)\n";
    echo "   4. Re-seed Database:          pnpm db:init (or php database/init.php)\n\n";
    exit(0);
}

// ----------------------------------------------------
// Web Browser UI Execution
// ----------------------------------------------------
$hasRun = false;
$outputData = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'scaffold') {
    $outputData = run_scaffolding($rootDir, $targetPathInput, false, $appName, false, $manifest);
    $hasRun = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($manifest['name'] ?? 'Feature Installer & Scaffolder') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/regular/style.css">
    <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/bold/style.css">
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex items-center justify-center p-4 antialiased selection:bg-emerald-500 selection:text-white">

    <div class="max-w-xl w-full bg-slate-950 border border-slate-800 rounded-3xl p-8 sm:p-10 shadow-2xl relative overflow-hidden">
        
        <!-- Background accents -->
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Header -->
        <div class="flex items-center gap-3.5 mb-6">
            <div>
                <h1 class="text-xl font-bold text-white tracking-tight"><?= htmlspecialchars($manifest['name'] ?? 'Feature Installer & Scaffolder') ?></h1>
                <p class="text-xs text-slate-400"><?= htmlspecialchars($manifest['description'] ?? 'Scaffold a Clean Online Store & Admin CMS to Your Chosen Folder') ?></p>
            </div>
        </div>

        <?php if ($hasRun && $outputData): ?>
            <!-- Results View -->
            <div class="space-y-3 mb-6 bg-slate-900/80 rounded-2xl p-5 border border-slate-800 text-xs">
                <p class="font-semibold text-slate-200 text-sm mb-2 flex items-center gap-2">
                    <i class="ph ph-check-circle text-emerald-400 text-base"></i> Scaffolding Completed Successfully!
                </p>
                <p class="text-slate-400 pb-2 border-b border-slate-800">
                    📍 <strong>Target Location:</strong> <code class="bg-slate-950 px-2 py-0.5 rounded text-emerald-400 font-mono"><?= htmlspecialchars($outputData['targetDir']) ?></code>
                </p>
                <?php foreach ($outputData['results'] as $res): ?>
                    <div class="flex items-start gap-2 text-slate-300">
                        <span><?= $res['type'] === 'success' ? '✅' : ($res['type'] === 'warn' ? '⚠️' : '❌') ?></span>
                        <div><?= $res['msg'] ?></div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="p-4 bg-emerald-950/40 border border-emerald-500/30 rounded-2xl mb-6 text-xs text-emerald-300">
                <p class="font-semibold mb-1">🔑 Default Admin Credentials:</p>
                <p>Username: <code class="bg-emerald-900/60 px-1.5 py-0.5 rounded text-white">admin</code> | Password: <code class="bg-emerald-900/60 px-1.5 py-0.5 rounded text-white">password123</code></p>
            </div>

            <?php if (!$outputData['isExternal']): ?>
                <div class="grid grid-cols-2 gap-3">
                    <a href="<?= function_exists('base_url') ? base_url('demo.php') : '../demo.php' ?>" class="flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-medium text-xs transition">
                        <i class="ph ph-storefront text-base"></i> Open Online Store
                    </a>
                    <a href="<?= function_exists('base_url') ? base_url('admin/login.php') : '../admin/login.php' ?>" class="flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-medium text-xs border border-slate-700 transition">
                        <i class="ph ph-shield-check text-base"></i> Admin Login
                    </a>
                </div>
            <?php else: ?>
                <div class="p-4 bg-slate-900 border border-slate-800 rounded-2xl text-xs space-y-2">
                    <p class="font-semibold text-white">🚀 How to Run Your New Project:</p>
                    <div class="bg-slate-950 p-3 rounded-xl font-mono text-[11px] text-slate-300 space-y-1">
                        <div>cd <?= htmlspecialchars($outputData['targetDir']) ?></div>
                        <div>php -S 0.0.0.0:8000</div>
                    </div>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <!-- Initial Form -->
            <form method="POST" action="index.php" class="space-y-4">
                <input type="hidden" name="action" value="scaffold">

                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-slate-300 flex items-center justify-between">
                        <span>Target Destination Path</span>
                        <span class="text-[11px] text-emerald-400 font-normal">e.g. <code>../new-store</code></span>
                    </label>
                    <div class="relative">
                        <i class="ph ph-folder text-slate-500 absolute left-3.5 top-3 text-base"></i>
                        <input type="text" id="target_path_input" name="target_path" value="../new-store" placeholder="e.g. ../new-store or /var/www/my-shop" class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-900 border border-slate-800 text-white text-xs font-mono focus:outline-none focus:border-emerald-500 transition" required>
                    </div>
                    <p class="text-[11px] text-slate-400">Copies clean store template (with demo as <code>index.php</code>) to the new destination without altering the current repository.</p>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-slate-300">Store / Brand Name</label>
                    <div class="relative">
                        <i class="ph ph-storefront text-slate-500 absolute left-3.5 top-3 text-base"></i>
                        <input type="text" id="app_name_input" name="app_name" value="<?= htmlspecialchars(format_folder_name_to_title('../new-store', 'My Store', $rootDir)) ?>" class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-900 border border-slate-800 text-white text-xs focus:outline-none focus:border-emerald-500 transition" required>
                    </div>
                </div>

                <div class="pt-3">
                    <button type="submit" class="w-full py-3 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs tracking-tight transition flex items-center justify-center gap-2 shadow-lg shadow-emerald-950">
                        <i class="ph-bold ph-lightning text-base"></i> Scaffold Store to New Folder
                    </button>
                </div>
            </form>

            <script>
                const targetInput = document.getElementById('target_path_input');
                const nameInput = document.getElementById('app_name_input');
                let userEditedName = false;

                nameInput.addEventListener('input', () => {
                    userEditedName = true;
                });

                targetInput.addEventListener('input', (e) => {
                    if (userEditedName) return;
                    let val = e.target.value.trim();
                    if (!val || val === '.' || val === './') {
                        val = '<?= basename($rootDir) ?>';
                    } else {
                        val = val.replace(/[\/\\]+$/, '').split(/[\/\\]/).pop();
                    }
                    let formatted = val.replace(/[_\-]+/g, ' ').trim();
                    if (formatted) {
                        nameInput.value = formatted.replace(/\b\w/g, l => l.toUpperCase());
                    }
                });
            </script>
        <?php endif; ?>

        <div class="mt-8 pt-5 border-t border-slate-800/80 text-center text-[11px] text-slate-500">
            Vanilla PHP UI Framework &bull; Modular Scaffolder &bull; Production Ready
        </div>

    </div>

</body>
</html>
