<?php
/**
 * Image Upload Helper
 */

function handle_image_upload(array $file, string $targetDir = ''): array {
    if (empty($targetDir)) {
        $targetDir = dirname(__DIR__) . '/uploads/products/';
    }

    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    // Check upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors = [
            UPLOAD_ERR_INI_SIZE   => 'Ukuran file melebihi batas upload_max_filesize server.',
            UPLOAD_ERR_FORM_SIZE  => 'Ukuran file melebihi batas formulir.',
            UPLOAD_ERR_PARTIAL    => 'File hanya terupload sebagian.',
            UPLOAD_ERR_NO_FILE    => 'Tidak ada file yang diunggah.',
            UPLOAD_ERR_NO_TMP_DIR => 'Folder temporary server tidak ditemukan.',
            UPLOAD_ERR_CANT_WRITE => 'Gagal menulis file ke disk server.',
            UPLOAD_ERR_EXTENSION  => 'Ekstensi file dihentikan oleh PHP.'
        ];
        return ['success' => false, 'message' => $errors[$file['error']] ?? 'Terjadi kesalahan upload.'];
    }

    // Check file size (max 3MB)
    $maxSize = 3 * 1024 * 1024;
    if ($file['size'] > $maxSize) {
        return ['success' => false, 'message' => 'Ukuran gambar maksimal 3 MB.'];
    }

    // Check extension
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($fileExt, $allowedExtensions)) {
        return ['success' => false, 'message' => 'Format file tidak diizinkan. Hanya JPG, PNG, WEBP, dan GIF.'];
    }

    // Check MIME type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    if (!in_array($mimeType, $allowedMimes)) {
        return ['success' => false, 'message' => 'Tipe konten file tidak valid.'];
    }

    // Generate unique file name
    $newFileName = 'prod_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $fileExt;
    $destination = rtrim($targetDir, '/') . '/' . $newFileName;

    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return [
            'success'  => true,
            'filename' => $newFileName,
            'filepath' => $destination
        ];
    }

    return ['success' => false, 'message' => 'Gagal menyimpan file gambar ke server.'];
}

function delete_uploaded_image(string $filename, string $targetDir = ''): bool {
    if (empty($filename) || str_starts_with($filename, 'http')) {
        return false;
    }
    if (empty($targetDir)) {
        $targetDir = dirname(__DIR__) . '/uploads/products/';
    }
    $fullPath = rtrim($targetDir, '/') . '/' . basename($filename);
    if (file_exists($fullPath) && is_file($fullPath)) {
        return unlink($fullPath);
    }
    return false;
}
