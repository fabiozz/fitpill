window.addEventListener('pageshow', async function(event) {
    try {
        const data = await checkSession();

        if (data === "False") {
            alert('Você precisa estar logado para acessar essa página!');
            location.href = 'login.html';
        } else {
            const data2fa = await checkSession2FA();
        }
    } catch (error) {
        console.error('Erro durante verificação ->', error);
    }
});

document.addEventListener('DOMContentLoaded', async function(event) {
    try {
        // Requisição AJAX para obter o plano
        const response = await fetch('php/treino.php');

        const data = await response.json();

        if (data.error) {
            alert(data.error);
        } else {
            const planTable = document.getElementById('planTable');
            data.forEach(dia => {
                const row = `<tr>
                                <td>${dia.dia}</td>
                                <td>${dia.treino}</td>
                            </tr>`;
                planTable.innerHTML += row;
            });
        }
    } catch (error) {
        console.error('Erro ao obter plano:', error);
        alert('Erro ao obter plano. Por favor, tente novamente.');
    }
});
