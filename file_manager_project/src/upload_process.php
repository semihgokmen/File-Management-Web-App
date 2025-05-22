<?php
require_once 'helpers.php';
checkSession();

date_default_timezone_set('Europe/Istanbul');

// uploads klasörü proje kökünde (src'nin bir üstü)
$uploadDir = dirname(__DIR__) . "/uploads";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$allowedTypes = ['application/pdf', 'image/png', 'image/jpeg'];
$uploadedFiles = [];

if (isset($_FILES['files'])) {
    foreach ($_FILES['files']['error'] as $key => $error) {
        if ($error === UPLOAD_ERR_OK) {
            $tmpName = $_FILES['files']['tmp_name'][$key];
            $name = basename($_FILES['files']['name'][$key]);
            $name = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $name);

            $destination = $uploadDir . "/" . $name;
            $counter = 1;
            while (file_exists($destination)) {
                $name = pathinfo($name, PATHINFO_FILENAME) . "_$counter." . pathinfo($name, PATHINFO_EXTENSION);
                $destination = $uploadDir . "/" . $name;
                $counter++;
            }

            if (move_uploaded_file($tmpName, $destination)) {
                $uploadedFiles[] = $name;
            }
        }
    }
}

if (count($uploadedFiles) > 0) {
    $jsonFile = "uploads.json";  // JSON dosyası hala src içinde
    $data = readJsonFile($jsonFile);

    foreach ($uploadedFiles as $uf) {
        $filepath = $uploadDir . "/" . $uf;
        $data[] = [
            'username' => $_SESSION['username'],
            'filename' => $uf,
            'uploaded_at' => date('Y-m-d H:i:s'),
            'size' => filesize($filepath),
        ];
    }

    writeJsonFile($jsonFile, $data);

    echo json_encode(['success' => true]);
    exit();
} else {
    echo json_encode(['success' => false]);
    exit();
}