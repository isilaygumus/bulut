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
    fetch("../backend/register.php?action=register", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            email: document.getElementById('uyeemail').value,
            name: document.getElementById('uyename').value,
            password: document.getElementById('uyepassword').value
        })
    })
        .then(res => res.json())
        .then(data => alert(data.status));
}

// Giriş Fonksiyonu
function login() {
    const emailVal = document.getElementById('email').value;
    const passVal = document.getElementById('password').value;

    fetch("../backend/register.php?action=login", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            email: emailVal,
            password: passVal
        })
    })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
            if (data.status === "success") {
                window.location.href = "profil.html"; // Giriş başarılıysa yönlendir
            }
        })
        .catch(err => console.error("Hata:", err));
}
