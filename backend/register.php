<?php
// Frontend'in API'ye erişebilmesi için gerekli CORS ayarları
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

// Veritabanı bağlantısı (Localhost ayarları)
// Bulut platformuna (AWS RDS vb.) geçtiğinde bu bilgileri güncelleyeceksin [cite: 7, 11]
$host = "localhost";
$user = "root";
$pass = "";
$db   = "user"; 

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Veritabanı bağlantı hatası"]));
}

// URL'den gelen action parametresini güvenli bir şekilde alıyoruz
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Frontend'den gelen JSON verisini okuyoruz
$input = file_get_contents("php://input");
$data = json_decode($input, true);

// --- KAYIT İŞLEMİ ---
if ($action == "register" && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $data['email'] ?? '';
    $name = $data['name'] ?? '';
    $password = isset($data['password']) ? password_hash($data['password'], PASSWORD_DEFAULT) : '';

    if (empty($email) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "Eksik bilgi gönderildi"]);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO users (email, name, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $email, $name, $password);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Kullanıcı başarıyla oluşturuldu"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Kayıt sırasında hata oluştu veya email zaten var"]);
    }
    $stmt->close();
}

// --- GİRİŞ İŞLEMİ ---
if ($action == "login" && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $data['email'] ?? '';
    $password = $data['password'] ?? '';

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
