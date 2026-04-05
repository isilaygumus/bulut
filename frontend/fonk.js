function go_to_enter() {
    window.location.href = "enter.html"
}

function go_to_cat() {
    window.location.href = "kategoriler.html"
}

function go_to_home() {
    windows.location.href = "anasayfa.html"
}

function go_to_koltuk() {
    window.location.href = "koltuk.html"
}

// Kayıt Fonksiyonu
function register() {
    const email = document.getElementById('uyeemail').value;
    const name = document.getElementById('uyename').value;
    const password = document.getElementById('uyepassword').value;

    fetch("http://16.171.112.168/backend/register.php?action=register", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ email: email, name: name, password: password })
    })
    .then(res => res.json())
    .then(data => {
        alert(data.status); 
    })
    .catch(err => {
        alert("Bağlantı hatası!");
        console.error("HATA:", err);
    });
}

// Giriş Fonksiyonu
function login() {
    const emailVal = document.getElementById('email').value;
    const passVal = document.getElementById('password').value;

    fetch("http://16.171.112.168/backend/register.php?action=login", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ email: emailVal, password: passVal })
    })
    .then(res => res.json())
    .then(data => {
        alert(data.status); 
        

        // Giriş başarılıysa profil sayfasına yönlendir

// alert'te ne yazıyorsa buraya tam olarak onu yaz (Büyük/küçük harfe duyarlıdır)
if (data.status === "Giriş Başarılı") {
    window.location.href = "profil.html"; 
}
    })
    .catch(err => {
        alert("Bağlantı hatası!");
        console.error("HATA:", err);
    });
}
