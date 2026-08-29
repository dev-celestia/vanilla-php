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

// Global Static File-Based Application & Theme Configuration
function app_config(?string $key = null, $default = null) {
    static $config = [
        // App / Store Info
        'store_name'          => 'Store Showcase',
        'store_slogan'        => 'Curated Modern Tech & Lifestyle',
        'store_description'   => 'A modern e-commerce showcase built with Vanilla PHP UI primitives, Alpine.js reactive cart, and instant WhatsApp ordering.',
        'whatsapp_number'     => '15552345678', // Format without plus
        'store_email'         => 'contact@store.local',
        'store_phone'         => '+1 (555) 234-5678',
        'store_address'       => '742 Evergreen Terrace, Springfield, OR 97477',
        'currency'            => '$',
        'instagram_url'       => 'https://instagram.com/',
        'facebook_url'        => 'https://facebook.com/',
        'hero_title'          => 'Discover Premium Curated Products',
        'hero_subtitle'       => 'Browse our curated catalog, add items to your interactive cart drawer, and place orders directly via WhatsApp.',
        'hero_badge'          => '✨ Featured Showcase Collection',

        // Theme & Design System (Static Configuration)
        // Options: 'zinc', 'emerald', 'blue', 'indigo', 'violet', 'rose', 'amber', 'teal', 'slate'
        'theme_primary_color' => 'zinc',

        // Corner Radius Preset (Static Configuration)
        // Options: 'sharp' (0px), 'subtle' (6px), 'standard' (12px), 'soft' (16px), 'round' (24px), 'pill' (9999px)
        'theme_radius'        => 'sharp',
    ];

    if ($key === null) {
        return $config;
    }
    return $config[$key] ?? $default;
}

if (!function_exists('config')) {
    function config(?string $key = null, $default = null) {
        return app_config($key, $default);
    }
}

// Load store settings (file config is single source of truth for theme; DB merges dynamic store info)
function get_settings(): array {
    static $settings = null;
    if ($settings !== null) {
        return $settings;
    }

    $defaultSettings = app_config();

    $db = getDB();
    if ($db) {
        try {
            $stmt = $db->query("SELECT setting_key, setting_value FROM settings");
            $dbSettings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
            if (!empty($dbSettings)) {
                // Ensure static file-based theme configuration always takes precedence
                unset($dbSettings['theme_primary_color'], $dbSettings['theme_radius']);
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
