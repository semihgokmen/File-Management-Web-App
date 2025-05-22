<?php
session_start();
if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'Yetkisiz erişim.']);
    exit();
}

$dataFile = __DIR__ . "/uploads.json";  // JSON dosyası src içinde
$uploadDir = dirname(__DIR__) . "/uploads";  // uploads klasörü proje kökünde

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $filename = $input['filename'] ?? '';

    if ($filename && file_exists("$uploadDir/$filename")) {
        // Dosyayı sil
        unlink("$uploadDir/$filename");

        // JSON dosyasını güncelle
        if (file_exists($dataFile)) {
            $data = json_decode(file_get_contents($dataFile), true) ?? [];
            $data = array_filter($data, function ($item) use ($filename) {
                return $item['filename'] !== $filename;
            });
            file_put_contents($dataFile, json_encode(array_values($data), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Dosya bulunamadı.']);
    }
    exit();
}