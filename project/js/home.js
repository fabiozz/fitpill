window.addEventListener('pageshow', async function(event) {
    try {
        const data = await checkSession();

        if (data === "False") {
            alert('Voce precisa estar logado para acessar essa página!');
            location.href = 'login.html';
        } else {
            const data2fa = await checkSession2FA();

            if (data2fa === "False") {
                
            }
        }
    } catch (error) {
        console.error('Erro durante verificacão ->', error);
    }
});