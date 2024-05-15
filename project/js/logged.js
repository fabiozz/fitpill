document.addEventListener("DOMContentLoaded", (event) => {
    checkLogged()
  });
  

async function checkLogged() {
    try {
        var response = await fetch("php/check_logged.php", {
            method: "POST",
            body: ""
        });
        var data = await response.text();
        if(data == "not_log"){
            alert("Usuário não logado");
            window.location.href = "login.html";
        }
        if(data == "session_to"){
            alert("Timeout de sessão");
            window.location.href = "login.html";
        }
    } catch (error) {
        console.error("Error:", error);
    }
}