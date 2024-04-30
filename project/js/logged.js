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
        alert(data)
        if(data == "not_log"){
            alert("Usuário não logado")
        }
    } catch (error) {
        console.error("Error:", error);
    }
}