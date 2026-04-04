<?php
// CORS Ayarları: Frontend'in API'ye erişebilmesi için şarttır
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Veritabanı bağlantısı
$conn = new mysqli("localhost", "root", "", "user");

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Bağlantı hatası"]));
}

// Hata düzeltme: isset kontrolü ile "action" parametresini güvenli alıyoruz
$action = isset($_GET['action']) ? $_GET['action'] : '';
$data = json_decode(file_get_contents("php://input"), true);

if ($action == "register") {
    $email = $data['email'] ?? '';
    $name = $data['name'] ?? '';
    $password = password_hash($data['password'] ?? '', PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (email, name, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $email, $name, $password);
    
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Kayıt başarılı"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Kayıt başarısız"]);
    }
}

if ($action == "login") {
    // ... giriş işlemleri ...
}
?>
