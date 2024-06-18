async function checkSession2FA() {
    try {
    const response = await fetch('php/check_2fa.php', {
    method: 'GET'
    });
    
        const responseText = await response.text();
        console.log('Server response:', responseText);
    
        const data = JSON.parse(responseText);
        return data;
    } catch (error) {
        console.error('Erro durante verificacao ->', error);
    }
}