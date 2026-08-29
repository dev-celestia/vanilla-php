<?php
/**
 * Database Initializer & Migration Tool
 * Jalankan via browser (http://localhost/database/init.php) atau terminal (php database/init.php)
 */

require_once __DIR__ . '/../config/database.php';

$isCli = (php_sapi_name() === 'cli');

function printMsg($msg, $isCli) {
    if ($isCli) {
        echo $msg . PHP_EOL;
    } else {
        echo "<p class='font-mono text-xs leading-relaxed my-1'>$msg</p>";
    }
}

if (!$isCli) {
    echo "<!DOCTYPE html><html lang='en'><head><meta charset='UTF-8'><title>Database Setup - Vanilla PHP</title>";
    echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
    echo "<script src='https://cdn.tailwindcss.com'></script>";
    echo "</head><body class='bg-slate-50 text-slate-800 p-6 min-h-screen flex items-center justify-center'>";
    echo "<div class='max-w-xl w-full bg-white rounded-2xl p-8 border border-slate-200'>";
    echo "<div class='flex items-center space-x-3 mb-6'><div class='w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold text-xl'>⚙️</div><h1 class='text-2xl font-bold text-slate-900'>Database Auto-Setup</h1></div>";
}

printMsg("🔄 Starting database initialization...", $isCli);

// 1. Connect to MySQL server (without selecting db first to create database if not exists)
$createdDb = false;
try {
    $pdoRoot = new PDO(
        sprintf('mysql:host=%s;port=%s;charset=utf8mb4', DB_HOST, DB_PORT),
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    $pdoRoot->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
    $createdDb = true;
    printMsg("✅ Database <strong>" . DB_NAME . "</strong> is ready on MySQL server.", $isCli);
} catch (PDOException $e) {
    printMsg("⚠️ MySQL Info: " . htmlspecialchars($e->getMessage()), $isCli);
}

// 2. Execute table schema & seed data
$db = getDB();

if ($db) {
    $sqlFile = __DIR__ . '/schema.sql';
    if (file_exists($sqlFile)) {
        $sql = file_get_contents($sqlFile);
        try {
            $db->exec($sql);
            printMsg("✅ All tables (`admins`, `settings`, `categories`, `products`, `orders`, `order_items`) and initial seed data imported successfully!", $isCli);
            printMsg("--------------------------------------------------", $isCli);
            printMsg("🔑 <strong>Default Admin Credentials:</strong>", $isCli);
            printMsg("   Username: <code>admin</code>", $isCli);
            printMsg("   Password: <code>password123</code>", $isCli);
            printMsg("--------------------------------------------------", $isCli);
        } catch (PDOException $e) {
            printMsg("❌ Failed to execute SQL: " . htmlspecialchars($e->getMessage()), $isCli);
        }
    } else {
        printMsg("❌ File `schema.sql` not found.", $isCli);
    }
} else {
    printMsg("❌ Unable to connect to MySQL database. Please verify MySQL is running and check <code>config/database.php</code>.", $isCli);
}

if (!$isCli) {
    echo "<div class='mt-6 pt-6 border-t border-slate-100 flex items-center justify-between'>";
    echo "<a href='../demo.php' class='inline-flex items-center px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-xl border border-emerald-500/20 transition'>Open Demo Store →</a>";
    echo "<a href='../admin/login.php' class='inline-flex items-center px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-medium rounded-xl border border-slate-800 transition'>Admin Login →</a>";
    echo "</div>";
    echo "</div></body></html>";
}
