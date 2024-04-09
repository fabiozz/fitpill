document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('register').addEventListener('submit', function(e) {
        e.preventDefault();
        submitRegister();
    });
});


async function submitRegister() {
    var password = document.getElementById("password");
    var hash = CryptoJS.SHA256(password).toString();

    var formData = new FormData(document.getElementById("register"));
    formData.set("password", hash);

    var user = formData.get("user");
    var email = formData.get("email");

    if (/[^A-Za-z0-9]/i.test(user)) {
        alert("Usuário não pode conter caracteres especiais");
        return;
    }
    if (!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email)) {
        alert("Email inválido!");
        return;
    }
    if (!/[A-Z]/.test(password)){
        alert("Senha deve conter ao menos uma letra maiúscula");
        return;
    }
    if (!/[a-z]/.test(password)){
        alert("Senha deve conter ao menos uma letra maiúscula");
        return;
    }
    //if (!/\d/.test(password)){
    //    alert("Senha deve conter ao menos um número");
    //    return;
    //}
    if (password.length < 8 || password.length > 20){
        alert("Senha deve ser maior que 8 caracteres e menor que 20");
        return;
    }

    try {
        var response = await fetch("php/register.php", {
            method: "POST",
            body: formData
        });

        // Verificar se a requisição foi bem-sucedida
        if (response.ok) {
            alert("Cadastro feito com sucesso");
        } else {
            alert("Error occurred, try again later...");
        }
    } catch (error) {
        console.error("Error:", error);
        alert("Error occurred, try again later...");
    }
}