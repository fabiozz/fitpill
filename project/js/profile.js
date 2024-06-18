window.addEventListener('pageshow', async function(event) {
    try {
        const data = await checkSession();

        if (data === "False") {
            alert('Você precisa estar logado para acessar essa página!');
            location.href = 'login.html';
        } else {
            const data2fa = await checkSession2FA();

            const profileData = await fetchProfileData();
            displayProfileData(profileData);
        }
    } catch (error) {
        console.error('Erro durante verificação ->', error);
    }
});

async function fetchProfileData() {
    try {
        const response = await fetch('php/profile.php', {
            method: 'POST',
            body: ''
        });

        if (!response.ok) {
            throw new Error('Failed to fetch profile data');
        }

        const data = response.json();
        return data;
    } catch (error) {
        console.error('Error fetching profile data:', error);
        throw error;
    }
}

function displayProfileData(profileData) {
    document.getElementById('name').textContent = profileData.usuario || 'N/A';
    document.getElementById('email').textContent = profileData.email || 'N/A';
    document.getElementById('weight').textContent = profileData.peso || 'N/A';
    document.getElementById('height').textContent = profileData.altura || 'N/A';
    document.getElementById('trainingDays').textContent = profileData.dias || 'N/A';
}


