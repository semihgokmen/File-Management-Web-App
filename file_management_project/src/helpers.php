<?php

// JSON dosyasını oku
function readJsonFile($filePath) {
    if (!file_exists($filePath)) {
        return [];
    }
    $content = file_get_contents($filePath);
    return json_decode($content, true) ?? [];
}

// JSON dosyasına yaz
function writeJsonFile($filePath, $data) {
    file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

// Oturum kontrolü
function checkSession() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['username'])) {
        header("Location: login_form.php");
        exit();
    }
}