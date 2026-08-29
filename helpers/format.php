<?php
/**
 * Formatting & Sanitization Helpers
 */

function format_rupiah(float|int|string|null $amount): string {
    $num = (float)($amount ?? 0);
    // If integer and large (like IDR), format with Rp or format as currency
    $settings = function_exists('get_settings') ? get_settings() : [];
    $currency = $settings['currency'] ?? '$';
    
    if ($currency === 'Rp' || $currency === 'IDR') {
        return 'Rp ' . number_format($num, 0, ',', '.');
    }
    
    // Default standard USD / English currency format
    if (floor($num) == $num) {
        return $currency . number_format($num, 0, '.', ',');
    }
    return $currency . number_format($num, 2, '.', ',');
}

function sanitize(?string $str): string {
    if ($str === null) return '';
    return htmlspecialchars(trim($str), ENT_QUOTES, 'UTF-8');
}

function slugify(string $text): string {
    // Replace non letter or digits by -
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    // Transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    // Remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);
    // Trim
    $text = trim($text, '-');
    // Remove duplicate -
    $text = preg_replace('~-+~', '-', $text);
    // Lowercase
    $text = strtolower($text);

    return empty($text) ? 'item-' . time() : $text;
}

function format_date(?string $datetime, string $format = 'M d, Y, H:i'): string {
    if (empty($datetime)) return '-';
    $timestamp = strtotime($datetime);
    if (!$timestamp) return $datetime;

    $months = [
        1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
        5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug',
        9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'
    ];

    $d = date('d', $timestamp);
    $m = (int)date('m', $timestamp);
    $y = date('Y', $timestamp);
    $time = date('H:i', $timestamp);

    return "$d {$months[$m]} $y, $time";
}

function generate_order_number(): string {
    return 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
}

