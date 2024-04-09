document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('trocar').addEventListener('submit', function(e) {
        e.preventDefault();
        changePass();
    });
});

async function changePass() {
    var formData = new FormData(document.getElementById("trocar"));
    var password = formData.get("password")
    var hash = CryptoJS.SHA256(password).toString();
    let params = new URL(document.location).searchParams;
    let token = params.get("token");

    formData.append("token", token);
    formData.set("password", hash);

    if (!/[A-Z]/.test(password)){
        alert("Senha deve conter ao menos uma letra maiúscula");
        return;
    }
    if (!/[a-z]/.test(password)){
        alert("Senha deve conter ao menos uma letra maiúscula");
        return;
    }

    try {
        var response = await fetch("php/recuperar.php", {
            method: "POST",
            body: formData
        });

        if (response.ok) {
            alert("Troca de senha realizada com sucesso!");
        } else {
            alert("Ocorreu um erro no envio!, tente novamente...");
        }
    } catch (error) {
        console.error("Error:", error);
    }
    window.location.replace("login.html");
}