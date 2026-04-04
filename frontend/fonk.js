function go_to_enter() {
    window.location.href = "../backend/register.php"
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
    fetch("../backend/register.php?action=login", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            email: document.getElementById('email').value,
            password: document.getElementById('password').value
        })
    })
    .then(res => res.json())
    .then(data => alert(data.status));
}
