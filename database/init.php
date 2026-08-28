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
        echo "<p style='font-family: monospace; line-height: 1.6; margin: 4px 0;'>$msg</p>";
    }
}

if (!$isCli) {
    echo "<!DOCTYPE html><html lang='id'><head><meta charset='UTF-8'><title>Database Setup - Native Shop</title>";
    echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
    echo "<script src='https://cdn.tailwindcss.com'></script>";
    echo "</head><body class='bg-slate-50 text-slate-800 p-6 min-h-screen flex items-center justify-center'>";
    echo "<div class='max-w-xl w-full bg-white rounded-2xl p-8 border border-slate-200'>";
    echo "<div class='flex items-center space-x-3 mb-6'><div class='w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold text-xl'>⚙️</div><h1 class='text-2xl font-bold text-slate-900'>Database Auto-Setup</h1></div>";
}

printMsg("🔄 Memulai inisialisasi database...", $isCli);

// 1. Coba koneksi ke MySQL server (tanpa database dulu untuk membuat database jika belum ada)
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
    printMsg("✅ Database <strong>" . DB_NAME . "</strong> berhasil disiapkan pada MySQL server.", $isCli);
} catch (PDOException $e) {
    printMsg("⚠️ Info MySQL: " . htmlspecialchars($e->getMessage()), $isCli);
}

// 2. Eksekusi skema tabel & seed data
$db = getDB();

if ($db) {
    $sqlFile = __DIR__ . '/schema.sql';
    if (file_exists($sqlFile)) {
        $sql = file_get_contents($sqlFile);
        try {
            $db->exec($sql);
            printMsg("✅ Semua tabel (`admins`, `settings`, `categories`, `products`, `orders`, `order_items`) dan data awal berhasil diimpor!", $isCli);
            printMsg("--------------------------------------------------", $isCli);
            printMsg("🔑 <strong>Akun Admin Default:</strong>", $isCli);
            printMsg("   Username: <code>admin</code>", $isCli);
            printMsg("   Password: <code>password123</code>", $isCli);
            printMsg("--------------------------------------------------", $isCli);
        } catch (PDOException $e) {
            printMsg("❌ Gagal mengeksekusi SQL: " . htmlspecialchars($e->getMessage()), $isCli);
        }
    } else {
        printMsg("❌ File `schema.sql` tidak ditemukan.", $isCli);
    }
} else {
    printMsg("❌ Tidak dapat tersambung ke database MySQL. Pastikan MySQL berjalan dan cek file <code>config/database.php</code>.", $isCli);
}

if (!$isCli) {
    echo "<div class='mt-6 pt-6 border-t border-slate-100 flex items-center justify-between'>";
    echo "<a href='../index.php' class='inline-flex items-center px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-xl border border-emerald-500/20 transition'>Buka Toko Online →</a>";
    echo "<a href='../admin/login.php' class='inline-flex items-center px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-medium rounded-xl border border-slate-800 transition'>Masuk Admin →</a>";
    echo "</div>";
    echo "</div></body></html>";
}
