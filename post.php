<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo "Method Not Allowed";
    exit;
}

if (!isset($_FILES['file'])|| !isset($_POST['dirName'])) {
    http_response_code(400);
    echo "No file payload received/directory name not provided.";
    exit;
}

$type = $_POST['type'];
if ($type == 'banner') {
    $file = $_FILES['file'];
    $dirName = $_POST['dirName'];

    $uploadDir = '../assets/banners/';

    if ($dirName === 'newentry') {
        $randomName = 'tmp-'.bin2hex(random_bytes(16));
        $fileName = $randomName . '.png';
        $uploadPath = $uploadDir . $fileName;
        if (move_uploaded_file($file['tmp_name'], $uploadPath)){
            echo "asuka,".$randomName;
        } else {
            http_response_code(500);
            echo "shinji";
        }
    } else {
        $fileName = $dirName . '.png';
        $uploadPath = $uploadDir . $fileName;
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            echo "rei,".time();
        } else {
            http_response_code(500);
            echo "shinji";
        }
    }
}

