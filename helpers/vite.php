<?php
/**
 * Vite Asset Loader Helper for Vanilla PHP
 * 
 * Provides transparent switching between Vite Dev Server (HMR) and Production Manifest.
 * Works with zero configuration across development and production environments.
 */

if (!function_exists('is_vite_dev')) {
    function is_vite_dev(): bool {
        static $isDev = null;
        if ($isDev !== null) {
            return $isDev;
        }

        // 1. Explicit override via constant or env
        if (defined('VITE_DEV')) {
            return $isDev = (bool) VITE_DEV;
        }
        if (getenv('APP_ENV') === 'production' || getenv('VITE_DEV') === 'false') {
            return $isDev = false;
        }

        // 2. Auto-probe local Vite dev server port (5173) with fast 20ms timeout
        $fp = @fsockopen('127.0.0.1', 5173, $errno, $errstr, 0.02);
        if ($fp) {
            fclose($fp);
            return $isDev = true;
        }

        return $isDev = false;
    }
}

if (!function_exists('vite_asset_url')) {
    function vite_asset_url(string $path): string {
        $path = ltrim($path, '/');
        if (function_exists('base_url')) {
            return base_url('dist/' . $path);
        }
        return '/dist/' . $path;
    }
}

if (!function_exists('vite')) {
    function vite(string $entry = 'resources/js/main.js'): string {
        $cleanEntry = ltrim($entry, './');

        // Development: serve from Vite dev server
        if (is_vite_dev()) {
            $devServer = 'http://localhost:5173';
            return sprintf(
                "<!-- Vite Dev Server (HMR) -->\n" .
                "<script type=\"module\" src=\"%s/@vite/client\"></script>\n" .
                "<script type=\"module\" src=\"%s/%s\"></script>\n",
                $devServer,
                $devServer,
                $cleanEntry
            );
        }

        // Production: resolve from manifest
        $distDir = dirname(__DIR__) . '/dist';
        $manifestPath = $distDir . '/.vite/manifest.json';
        if (!file_exists($manifestPath)) {
            $manifestPath = $distDir . '/manifest.json';
        }

        if (!file_exists($manifestPath)) {
            return "<!-- Vite: Production manifest not found. Run 'npm run build' or 'npm run dev' -->\n";
        }

        $manifest = json_decode((string) file_get_contents($manifestPath), true);
        if (!is_array($manifest) || !isset($manifest[$cleanEntry])) {
            return "<!-- Vite: Entry '{$cleanEntry}' not found in manifest -->\n";
        }

        $chunk = $manifest[$cleanEntry];
        $output = '';

        // CSS stylesheets
        if (!empty($chunk['css']) && is_array($chunk['css'])) {
            foreach ($chunk['css'] as $cssFile) {
                $cssUrl = htmlspecialchars(vite_asset_url($cssFile), ENT_QUOTES, 'UTF-8');
                $output .= sprintf("<link rel=\"stylesheet\" href=\"%s\">\n", $cssUrl);
            }
        }

        // Main JavaScript module
        if (!empty($chunk['file'])) {
            $jsUrl = htmlspecialchars(vite_asset_url($chunk['file']), ENT_QUOTES, 'UTF-8');
            $output .= sprintf("<script type=\"module\" src=\"%s\"></script>\n", $jsUrl);
        }

        return $output;
    }
}
