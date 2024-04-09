document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('login').addEventListener('submit', function(e) {
        e.preventDefault();
        submitLogin();
    });
});

async function submitLogin() {
    var formData = new FormData(document.getElementById("login"));
    var password = formData.get("password")
    var hash = CryptoJS.SHA256(password).toString();
    formData.set("password", hash);

    try {
        var response = await fetch("php/login.php", {
            method: "POST",
            body: formData
        });

        var data = await response.text();

        if (data.includes("Login Sucedido!")) {
            alert("Login sucedido!");
        } else {
            alert("Erro no login");
        }
    } catch (error) {
        console.error("Error:", error);
    }
}