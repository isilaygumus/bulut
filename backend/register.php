<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

$host = "localhost";
$user = "root";
$pass = "";
$db   = "user"; 

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Bağlantı hatası"]));
}

$action = isset($_GET['action']) ? $_GET['action'] : '';
$input = file_get_contents("php://input");
$data = json_decode($input, true);

// --- KAYIT ---
if ($action == "register") {
    $email = $data['email'] ?? '';
    $name = $data['name'] ?? '';
    $password = isset($data['password']) ? password_hash($data['password'], PASSWORD_DEFAULT) : '';

    // TABLO ADI DÜZELTİLDİ: kullanici
    $stmt = $conn->prepare("INSERT INTO kullanici (email, name, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $email, $name, $password);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Kayıt başarılı"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Hata veya Email zaten var"]);
    }
}

// --- GİRİŞ ---
if ($action == "login") {
    $email = $data['email'] ?? '';
    $password = $data['password'] ?? '';

    // TABLO ADI DÜZELTİLDİ: kullanici
    $stmt = $conn->prepare("SELECT * FROM kullanici WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            echo json_encode(["status" => "success", "message" => "Giriş başarılı"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Şifre yanlış"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Kullanıcı yok"]);
    }
}
$conn->close();
?>
