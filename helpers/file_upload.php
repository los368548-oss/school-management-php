<?php

class FileUpload {
    public static function upload($file, $destination, $allowedTypes = ['jpg', 'png', 'pdf']) {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['error' => 'Upload error'];
        }

        $fileName = basename($file['name']);
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($fileExt, $allowedTypes)) {
            return ['error' => 'Invalid file type'];
        }

        $target = $destination . uniqid() . '.' . $fileExt;
        if (move_uploaded_file($file['tmp_name'], $target)) {
            return ['success' => $target];
        } else {
            return ['error' => 'Failed to move file'];
        }
    }
}