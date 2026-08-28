<?php
/**
 * Lightweight Framework Core Utilities & Helper Functions
 * Provides essential micro-framework abstractions for Request, Response, Database, and Flow Control.
 */

require_once __DIR__ . '/../config/database.php';

/**
 * Get the PDO database singleton connection instance.
 */
function db(): ?PDO {
    return getDB();
}

/**
 * Execute a parameterized database query and return PDOStatement.
 */
function db_query(string $sql, array $params = []): ?PDOStatement {
    $pdo = db();
    if (!$pdo) return null;
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    } catch (PDOException $e) {
        error_log('Database query error: ' . $e->getMessage());
        return null;
    }
}

/**
 * Execute a query and fetch a single record.
 */
function db_fetch(string $sql, array $params = []): ?array {
    $stmt = db_query($sql, $params);
    if (!$stmt) return null;
    $result = $stmt->fetch();
    return $result ?: null;
}

/**
 * Execute a query and fetch all matching records.
 */
function db_fetch_all(string $sql, array $params = []): array {
    $stmt = db_query($sql, $params);
    if (!$stmt) return [];
    return $stmt->fetchAll() ?: [];
}

/**
 * Retrieve a request parameter (GET, POST, or JSON body) with fallback.
 */
function request(?string $key = null, mixed $default = null): mixed {
    static $input = null;
    if ($input === null) {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (str_contains($contentType, 'application/json')) {
            $raw = file_get_contents('php://input');
            $json = json_decode($raw, true);
            $input = is_array($json) ? array_merge($_GET, $_POST, $json) : array_merge($_GET, $_POST);
        } else {
            $input = array_merge($_GET, $_POST);
        }
    }

    if ($key === null) {
        return $input;
    }

    return $input[$key] ?? $default;
}

/**
 * Check if the current HTTP request method is POST.
 */
function is_post(): bool {
    return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
}

/**
 * Check if the current HTTP request method is GET.
 */
function is_get(): bool {
    return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'GET';
}

/**
 * Check if the current request is an AJAX / fetch request.
 */
function is_ajax(): bool {
    return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
        || str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json');
}

/**
 * Return a JSON response and terminate script execution.
 */
function json_response(mixed $data, int $status = 200, array $headers = []): never {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    foreach ($headers as $key => $val) {
        header("$key: $val");
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

/**
 * Redirect to a specific URL with an HTTP status code.
 */
function redirect(string $url, int $status = 302): never {
    header('Location: ' . $url, true, $status);
    exit;
}

/**
 * Terminate execution with an HTTP error status code.
 */
function abort(int $status = 404, string $message = ''): never {
    http_response_code($status);
    if (!empty($message)) {
        echo $message;
    } else {
        $messages = [
            400 => '400 Bad Request',
            401 => '401 Unauthorized',
            403 => '403 Forbidden',
            404 => '404 Not Found',
            500 => '500 Internal Server Error',
        ];
        echo $messages[$status] ?? "HTTP Error $status";
    }
    exit;
}

/**
 * Get an environment variable with default fallback.
 */
function env(string $key, mixed $default = null): mixed {
    $val = getenv($key);
    if ($val === false) {
        $val = $_ENV[$key] ?? $_SERVER[$key] ?? null;
    }
    return $val !== null ? $val : $default;
}
