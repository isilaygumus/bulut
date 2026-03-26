<html>
<head>
    <meta charset="UTF-8">
    <title>Giriş ve Kayıt</title>
    <link rel="stylesheet" href="enter.css">
    <script src="fonk.js"></script>
</head>
<body>

    <header>
        <div class="logo">
            <a href="anasayfa.html">bilet.com</a>
        </div>
        <nav>
            <button onclick="go_to_cat()">KATEGORİLER</button>
        </nav>
    </header>

    <main>

    <div class="container">
      
      <div class="form-box">
        <h2>ÜYE GİRİŞİ</h2>
        <form  method="post">
          <input type="text" id="email" name="email" autocomplete="on" placeholder="email" required>
          <input type="password" id="password" name="password" placeholder="şifre" required>
          <button type="submit" name="login" >Giriş Yap</button>
          <p class="error" id="error-message">E-posta veya şifre hatalı!</p>
        </form>
      </div>

      <div class="form-box">
        <h2>ÜYE OL</h2>
        <form id="register_form"  method="POST">
          <input type="text" autocomplete="on" id="uyeemail" name="email" placeholder="email" required>
          <input type="text" autocomplete="on" id="uyename" name="name" placeholder="isim soyisim" required>
          <input type="password" id="uyepassword" name="password" placeholder="şifre" required>
          <button type="submit" name="register" id="kayitbtn">Kayıt Ol</button>
        </form>
      </div>
    </main>

    <footer>
        <div class="contact-info">
            <br><strong>Işılay Gümüş</strong><br><br>
            E-posta: isilaygumus@gmail.com<br><br>
        </div>
    </footer>

</body>
</html>

<?php
$servername = "localhost"; 
$username = "root";        
$password = "";            
$dbname = "user";   

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Bağlantı başarısız: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $email = $_POST['email'];
    $name = $_POST['name'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 

    $sql = "SELECT * FROM users WHERE email='$email'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Bu e-posta adresi zaten kayıtlı!";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (email, name, password) VALUES ( $email, $name, $password)");
        $stmt->bind_param("sss", $email, $name, $password);
        if ($stmt->execute()) {
            echo "Yeni kayıt başarıyla oluşturuldu!";
        } else {
            echo "Hata: " . $stmt->error;
        }
    }
    $stmt->close();
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
        $hashed_password = $row['password'];

        if (password_verify($password, $row['password'])) {
            header("Location: profil.html");
            exit(); 

        } else {
            echo "Hatalı Şifre";
        }
    } else {
        echo "Kullanıcı bulunamadı.";
    }
}

$sql = "SELECT id, name, email FROM users";
$result = $conn->query($sql);
$conn->close();
?>