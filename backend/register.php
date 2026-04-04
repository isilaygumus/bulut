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
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Bağlantı hatası"]));
}

$action = isset($_GET['action']) ? $_GET['action'] : '';
$input = file_get_contents("php://input");
$data = json_decode($input, true);

// --- KAYIT İŞLEMİ ---
if ($action == "register" && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $data['email'] ?? '';
    $name = $data['name'] ?? '';
    $password = isset($data['password']) ? password_hash($data['password'], PASSWORD_DEFAULT) : '';

    // Tablo adı 'kullanici' olarak güncellendi
    $stmt = $conn->prepare("INSERT INTO users (email, name, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $email, $name, $password);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Kullanıcı başarıyla oluşturuldu"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Hata oluştu veya email kayıtlı"]);
    }
    $stmt->close();
}

// --- GİRİŞ İŞLEMİ ---
if ($action == "login" && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $data['email'] ?? '';
    $password = $data['password'] ?? '';

    // Tablo adı 'kullanici' olarak güncellendi
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            echo json_encode(["status" => "success", "message" => "Giriş başarılı"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Hatalı şifre"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Kullanıcı bulunamadı"]);
    }
    $stmt->close();
}
$conn->close();
?>
