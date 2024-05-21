document.getElementById('auth-form').addEventListener('submit', function(event) {
    event.preventDefault(); 

    var otp = document.getElementById('otp').value;
    var user = document.getElementById('user').value; 
    var formData = new FormData();
    formData.append('otp', otp);
    formData.append('user', user);

    fetch('autenticar.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        var resultado = document.getElementById('resultado');
        if (data.status === 'success') {
            resultado.innerHTML = `<p>${data.message}</p>`;
            resultado.style.color = 'green';
            // Redirecionar para a página de sucesso
            setTimeout(() => {
                window.location.href = 'logged.html';
            }, 2000);
        } else {
            resultado.innerHTML = `<p>${data.message}</p>`;
            resultado.style.color = 'red';
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});
