<?php
/**
 * Formatting & Sanitization Helpers
 */

function format_rupiah(float|int|string|null $amount): string {
    $num = (float)($amount ?? 0);
    return 'Rp ' . number_format($num, 0, ',', '.');
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

function format_date(?string $datetime, string $format = 'd M Y, H:i'): string {
    if (empty($datetime)) return '-';
    $timestamp = strtotime($datetime);
    if (!$timestamp) return $datetime;

    $months = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];

    $d = date('d', $timestamp);
    $m = (int)date('m', $timestamp);
    $y = date('Y', $timestamp);
    $time = date('H:i', $timestamp);

    return "$d {$months[$m]} $y, $time WIB";
}

function generate_order_number(): string {
    return 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
}
