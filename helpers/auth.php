<?php
/**
 * Authentication & Admin Guard Helper
 */

require_once __DIR__ . '/../config/app.php';

function is_admin_logged_in(): bool {
    return !empty($_SESSION['admin_id']) && !empty($_SESSION['admin_username']);
}

function get_current_admin(): ?array {
    if (!is_admin_logged_in()) {
        return null;
    }
    return [
        'id'       => $_SESSION['admin_id'],
        'username' => $_SESSION['admin_username'],
        'name'     => $_SESSION['admin_name'] ?? 'Administrator',
        'email'    => $_SESSION['admin_email'] ?? ''
    ];
}

function require_admin_auth(): void {
    if (!is_admin_logged_in()) {
        $_SESSION['flash_error'] = 'Silakan login terlebih dahulu untuk mengakses dashboard.';
        header('Location: ' . base_url('admin/login.php'));
        exit;
    }
}

function redirect_if_logged_in(): void {
    if (is_admin_logged_in()) {
        header('Location: ' . base_url('admin/index.php'));
        exit;
    }
}

function set_flash(string $type, string $message): void {
    $_SESSION['flash_' . $type] = $message;
}

function get_flash(string $type): ?string {
    $key = 'flash_' . $type;
    if (isset($_SESSION[$key])) {
        $msg = $_SESSION[$key];
        unset($_SESSION[$key]);
        return $msg;
    }
    return null;
}
