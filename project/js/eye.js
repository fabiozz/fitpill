let passwordButton = document.getElementById('eye');

passwordButton.addEventListener('click', () => {

    let passwordInp = document.getElementById('password');

    passwordButton.classList.toggle('fa-eye');
    passwordButton.classList.toggle('fa-eye-slash');

    passwordInp.type = passwordInp.type == 'password' ? 'text' : 'password'
})