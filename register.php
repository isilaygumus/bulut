<?php

header('Content-Type: application/json');

$servername = "localhost";
$username = "root";        
$password = "";            
$dbname = "user";   

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Bağlantı başarısız"]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $email = $_POST['email'];
    $name = $_POST['name'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Bu e-posta zaten kayıtlı!"]);
    } else {
        $stmt = $conn->prepare("INSERT INTO users (email, name, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $email, $name, $password);
        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Kayıt başarıyla oluşturuldu!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Hata: " . $stmt->error]);
        }
    }
    $stmt->close();
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            echo json_encode(["status" => "success", "redirect" => "profil.html"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Hatalı Şifre"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Kullanıcı bulunamadı."]);
    }
    $stmt->close();
    exit;
}
$conn->close();
?>
