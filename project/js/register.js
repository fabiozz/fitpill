document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('register').addEventListener('submit', function(e) {
        e.preventDefault();
        submitRegister();
    });
});


function submitRegister() {
    var password = document.getElementById("password");
    var hash = CryptoJS.SHA256(password).toString();

    var formData = new FormData(document.getElementById("register"));
    formData.set("password", hash);

    fetch('php/register.php', {
        method: "POST",
        body: formData,
    }).then(response => response.text()).then(data => {
        alert(data);
    }).catch(error => {
        console.error('Error:', error);
    });
}