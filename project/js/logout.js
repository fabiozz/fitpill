document.addEventListener('DOMContentLoaded', () => {
    const logoutForm = document.getElementById('logoutForm');
    logoutForm.addEventListener('submit', handleLogout);
});

async function logout(event) {
    event.preventDefault();

    try {
        const response = await fetch('php/logout.php', {
            method: 'POST',
            body: ''
        });

        const data = await response.text();
        console.log(data);
        alert(data);

        window.location.href = 'login.html';
    } catch (error) {
        console.error('Error:', error);
    }
}
