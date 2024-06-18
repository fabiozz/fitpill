document.addEventListener('DOMContentLoaded', () => {
    const logoutButton = document.getElementById('logoutButton');
    if (logoutButton) {
        logoutButton.addEventListener('click', logout);
    }
});

async function logout() {
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