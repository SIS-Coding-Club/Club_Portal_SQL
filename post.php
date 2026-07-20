<?php
$secret = require __DIR__ . '/auth/secret.php';
$host = $secret['host'];
$username = $secret['username'];
$password = $secret['password'];
$dbname = $secret['dbname'];

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    http_response_code(500);
    exit('Database connection failed.');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo "Method Not Allowed";
    exit;
}

$type = $_POST['type'];
if ($type == 'banner') {
    if (!isset($_FILES['file'])|| !isset($_POST['dirName'])) {
        http_response_code(400);
        echo "No file payload received/directory name not provided.";
        exit;
    }
    $file = $_FILES['file'];
    $dirName = $_POST['dirName'];

    $uploadDir = 'assets/banners/';

    if ($dirName === 'newentry') {
        $randomName = 'tmp-'.bin2hex(random_bytes(16));
        $fileName = $randomName . '.png';
        $uploadPath = $uploadDir . $fileName;
        if (move_uploaded_file($file['tmp_name'], $uploadPath)){
            echo "asuka,".$randomName;
        } else {
            http_response_code(500);
            echo "shinji-01";
        }
    } else {
        $fileName = $dirName . '.png';
        $uploadPath = $uploadDir . $fileName;
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            echo "rei,".time();
        } else {
            http_response_code(500);
            echo "shinji-13".$uploadPath;
        }
    }
} else if ($type == 'club') {
    $dirName = $_POST['dirName'];
    if ($dirName === 'newentry') {
        echo "asuka";
    } else {
        $sql = "SELECT * FROM clubs WHERE DirName = '$dirName'";
        $result = $conn->query($sql);
        if (!$result) {
            echo "shinji-01";
        }
        $row = $result->fetch_assoc();
        $Name = $row['Name'];
        $ClubTypes = $row['ClubType'];
        $Summary = $row['Summary'];
        $About = $row['About'];
        $MeetDay = $row['MeetDay'];
        $Location = $row['Location'];
        $MemberCount = $row['MemberCount'];
        $Advisors = $row['Advisors'];
        $Executives = $row['Executives'];
        $Instagram = $row['Instagram'];
        $Youtube = $row['Youtube'];
        $Website = $row['Website'];
        $Social = $row['Social'];
        $response = $Name . ';' . $ClubTypes . ';' . $Summary . ';' . $About . ';' . $MeetDay . ';' . $Location . ';' . $MemberCount . ';' . $Advisors . ';' . $Executives . ';' . $Instagram . ';' . $Youtube . ';' . $Website . ';' . $Social;
        if ($response !== '') {
            echo "rei;".$response;
        } else {
            echo "shinji-13";
        }
    }
}

