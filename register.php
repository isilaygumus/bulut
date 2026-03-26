<?php
$servername = "localhost"; 
$username = "root";        
$password = "";            
$dbname = "user";   

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Bağlantı başarısız: " . $conn->connect_error);
}

// KAYIT İŞLEMİ
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $email = $_POST['email'];
    $name = $_POST['name'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 

    $sql = "SELECT * FROM users WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Bu e-posta adresi zaten kayıtlı!";
    } else {
        // Senin kodundaki INSERT yapısını prepared statement ile güvenli hale getirdik
        $stmt = $conn->prepare("INSERT INTO users (email, name, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $email, $name, $password);
        if ($stmt->execute()) {
            echo "Yeni kayıt başarıyla oluşturuldu!";
        } else {
            echo "Hata: " . $stmt->error;
        }
    }
    $stmt->close();
}

// GİRİŞ İŞLEMİ
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
            header("Location: profil.html");
            exit(); 
        } else {
            echo "Hatalı Şifre";
        }
    } else {
        echo "Kullanıcı bulunamadı.";
    }
    $stmt->close();
}

$conn->close();
?>
