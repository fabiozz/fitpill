document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('recuperar').addEventListener('submit', function(e) {
        e.preventDefault();
        forgotPass();
    });
});

async function forgotPass() {
    var formData = new FormData(document.getElementById("recuperar"));
    try {
        var response = await fetch("php/envio_recuperar.php", {
            method: "POST",
            body: formData
        });

        if (response.ok) {
            alert("E-mail enviado para troca de senha! Verifique sua caixa de entrada");
        } else {
            alert("Ocorreu um erro no envio!, tente novamente...");
        }
    } catch (error) {
        console.error("Error:", error);
    }
}
