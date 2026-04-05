<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = "database-1.clek8syqwxg3.eu-north-1.rds.amazonaws.com";
$user = "admin";
$pass = "Okay2012."; // BURAYA RDS ŞİFRENİ YAZ
$db   = "user";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("ASIL HATA ŞU: " . $conn->connect_error);
}    

$action = isset($_GET['action']) ? $_GET['action'] : '';
$data = json_decode(file_get_contents("php://input"), true);

if ($action == 'register') {
    $name = $data['name'];
    $email = $data['email'];
    $password = $data['password'];

    $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "Kayıt Başarılı"]);
    } else {
        echo json_encode(["status" => "Kayıt Hatası"]);
    }
} 

if ($action == 'login') {
    $email = $data['email'];
    $password = $data['password'];

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo json_encode(["status" => "Giriş Başarılı"]);
    } else {
        echo json_encode(["status" => "Hatalı Giriş Bilgileri"]);
    }
}

$conn->close();
?>
