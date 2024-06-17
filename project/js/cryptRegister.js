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

        if (data === "True") {
            alert('Voce já está logado! Redirecionando para página principal.');
            location.href = 'home1.html';
        }
    } catch (error) {
        console.error('Erro durante verificacão ->', error);
    }
});

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('register').addEventListener('submit', function(e) {
        e.preventDefault();
        submitRegister();
    });
});

async function submitRegister() {
    var formData = new FormData(document.getElementById("register"));
    var password = formData.get("password")
    var hash = CryptoJS.SHA256(password).toString();
    formData.set("password", hash);

    var user = formData.get("user");
    var email = formData.get("email");

    if (/[^A-Za-z0-9]/i.test(user)) {
        alert("Usuário não pode conter caracteres especiais");
        return;
    }
    if (!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email)) {
        alert("Email inválido!");
        return;
    }
    if (!/[A-Z]/.test(password)){
        alert("Senha deve conter ao menos uma letra maiúscula");
        return;
    }
    if (!/[a-z]/.test(password)){
        alert("Senha deve conter ao menos uma letra maiúscula");
        return;
    }
    if (password.length < 8 || password.length > 20){
        alert("Senha deve ser maior que 8 caracteres e menor que 20");
        return;
    }

    var formDataObject = {};
    formData.forEach((value, key) => formDataObject[key] = value);

    try {
        const encryptedData = encryptAES(formDataObject, aesKey);
        const response = await fetch("php/register.php", {
            method: "POST",
            body: JSON.stringify({
                iv: encryptedData.iv,
                data: encryptedData.data
            })
        });

        if (response.ok) {
            var res = await response.json();

            if (res.error) {
                alert(responseData.error);
            } else {
                alert("Cadastro feito com sucesso! Verifique sua caixa de entrada para verificação");
                window.location.href = 'login.html';
            }
        } else {
            alert("Ocorreu um erro no envio! tente novamente...");
        }
    } catch (error) {
        console.error('Erro:', error);
        alert('Ocorreu um erro ao processar sua solicitação. Por favor, tente novamente.');
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