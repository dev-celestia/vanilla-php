<?php
/**
 * Application Global Configuration & Helpers Loader
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/theme.php';
require_once __DIR__ . '/../helpers/framework.php';
require_once __DIR__ . '/../ui/index.php';
require_once __DIR__ . '/../helpers/vite.php';

// Base URL detection
function base_url(string $path = ''): string {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)) ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    
    // Auto-detect subfolder path
    $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    $docRoot = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT'] ?? '');
    $baseDir = str_replace($docRoot, '', dirname(__DIR__));
    $baseDir = trim($baseDir, '/');
    
    $baseUrl = $protocol . $host . ($baseDir ? '/' . $baseDir : '');
    return rtrim($baseUrl, '/') . '/' . ltrim($path, '/');
}

// Upload URL helper
function upload_url(string $filename = ''): string {
    if (empty($filename)) {
        return 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&auto=format&fit=crop&q=80';
    }
    if (str_starts_with($filename, 'http://') || str_starts_with($filename, 'https://')) {
        return $filename;
    }
    return base_url('uploads/products/' . $filename);
}

// Load store settings from database with default fallback
function get_settings(): array {
    static $settings = null;
    if ($settings !== null) {
        return $settings;
    }

    $defaultSettings = [
        'store_name'        => 'KatalogStore',
        'store_slogan'      => 'slogan',
        'store_description' => 'Toko online terpercaya menyediakan aneka produk berkualitas dengan kemudahan pemesanan langsung melalui WhatsApp.',
        'whatsapp_number'   => '6281234567890', // Format 628...
        'store_email'       => 'kontak@katalogstore.id',
        'store_phone'       => '+62 812-3456-7890',
        'store_address'     => 'Jl. Sudirman No. 123, Jakarta Pusat, DKI Jakarta 10220',
        'currency'          => 'Rp',
        'instagram_url'     => 'https://instagram.com/',
        'facebook_url'      => 'https://facebook.com/',
        'hero_title'        => 'Katalog Produk Pilihan Berkualitas',
        'hero_subtitle'     => 'Pilih barang favorit Anda, masukkan ke keranjang, dan pesan instan via WhatsApp langsung ke admin kami.',
        'hero_badge'        => '✨ Promo Spesial Bulan Ini',
        'theme_primary_color' => 'zinc',
        'theme_radius'      => 'standard'
    ];

    $db = getDB();
    if ($db) {
        try {
            $stmt = $db->query("SELECT setting_key, setting_value FROM settings");
            $dbSettings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
            if (!empty($dbSettings)) {
                $settings = array_merge($defaultSettings, $dbSettings);
                return $settings;
            }
        } catch (PDOException $e) {
            // fallback to default
        }
    }

    $settings = $defaultSettings;
    return $settings;
}
