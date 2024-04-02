document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('login').addEventListener('submit', function(e) {
        e.preventDefault();
        submitLogin();
    });
});

function submitLogin() {
    var password = document.getElementById("password");
    var hash = CryptoJS.SHA256(password).toString();

    var formData = new FormData(document.getElementById("login"));
    formData.set("password", hash);

    fetch('php/login.php', {
        method: "POST",
        body: formData,
    }).then(response => response.text()).then(data => {
        alert(data);
    }).catch(error => {
        console.error('Error:', error);
    });
}