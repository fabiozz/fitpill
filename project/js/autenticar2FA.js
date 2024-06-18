let aesKey;

window.addEventListener('pageshow', async function(event) {

    (async function() {
        try {
            aesKey = await sendAESKey();
            console.log('Chave AES enviada! ->',aesKey);
        } catch (error) {
            console.error('Erro no envio da chave! ->', error);
        }
    }) ();

    try {
        const data = await checkSession();

        if (data === "False") {
            alert('Voce nao está logado! Redirecionando para página de login.');
            location.href = 'login.html';
        }
    } catch (error) {
        console.error('Erro durante verificacão ->', error);
    }
});

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('auth-form').addEventListener('submit', function(e) {
        e.preventDefault();
        verifyOTP();
    });
});

async function verifyOTP() {
    var formData = new FormData(document.getElementById("auth-form"));

    var formDataObject = {};
    formData.forEach((value, key) => formDataObject[key] = value);

    try {
        const encryptedData = encryptAES(formDataObject, aesKey);
        const response = await fetch("php/autenticar.php", {
            method: "POST",
            body: JSON.stringify({
                iv: encryptedData.iv,
                data: encryptedData.data
            })
        });

        const res = await response.json();
        const resultadoDiv = document.getElementById('resultado');

        if (res.error) {
            resultadoDiv.innerHTML = res.error;
            resultadoDiv.style.color = 'red';
        } else if (res.message) {
            resultadoDiv.innerHTML = res.message;
            resultadoDiv.style.color = 'green';
            alert("Usuário verificado com sucesso!");
            window.location.href = 'homep.html'
        }
    } catch (error) {
        console.error('Erro:', error);
        alert('Ocorreu um erro ao tentar autenticar.');
    }
}

async function getCertificate() {
    const response = await fetch('php/get_certificate.php');
    const certificate = await response.text();
    return certificate;
}

function extractPublicKey(cert) {
    const certificate = forge.pki.certificateFromPem(cert);
    const publicKey = forge.pki.publicKeyToPem(certificate.publicKey);
    return publicKey;
}

function generateAES() {
    return CryptoJS.lib.WordArray.random(32).toString(CryptoJS.enc.Hex);
}

function encryptAES(data, aesKey) {
    const dataString = JSON.stringify(data);

    const iv = CryptoJS.lib.WordArray.random(16);

    const encryptedData = CryptoJS.AES.encrypt(dataString, CryptoJS.enc.Hex.parse(aesKey), {
        iv: iv,
        mode: CryptoJS.mode.CBC,
        padding: CryptoJS.pad.Pkcs7
    });

    const result = {
        iv: iv.toString(CryptoJS.enc.Hex),
        data: encryptedData.toString()
    };
    console.log('Resultado da criptografia ->', result);
    return result;
}

async function sendAESKey() {
    const certificate = await getCertificate();
    const publicKey = extractPublicKey(certificate);

    const encrypt = new JSEncrypt();
    encrypt.setPublicKey(publicKey);

    const aesKey = generateAES();
    const encryptedAESKey = encrypt.encrypt(aesKey);

    const response = await fetch('php/post_secret_key.php', {
        method: 'POST',
        body: JSON.stringify({
            aes: encryptedAESKey
        })
    });

    if (response.ok) {
        console.log('Chave AES enviada para o servidor')
        return aesKey
    } else {
        throw new Error('Erro no envio da chave AES!')
    }

}
