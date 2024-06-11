document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('register').addEventListener('submit', function(e) {
        e.preventDefault();
        submitRegister();
    });
});


async function getPublicKey() {
    try {
        var response = await fetch('php/public_key.php', {
            method: 'GET'
          })
        if (response.ok) {
            var res = await response.json()
            const public_key = res.public_key;
            return public_key
        } else {
            alert("Ocorreu um erro!, tente novamente...");
        }
    } catch (error) {
        console.error("Error:", error);
    }
}

async function encryptData(data) {
	try {
		var aes_key = CryptoJS.lib.WordArray.random(32);
		var aes_iv = CryptoJS.lib.WordArray.random(16);

		var dataEnc = CryptoJS.AES.encrypt(data, aes_key, {
			iv: aes_iv,
		});

		var dataEncBase64 = CryptoJS.enc.Base64.stringify(dataEnc.ciphertext);

		var aes_key_hex = CryptoJS.enc.Hex.stringify(aes_key);
		var aes_iv_hex = CryptoJS.enc.Hex.stringify(aes_iv);

		console.log("encrypted data: " + dataEncBase64);
		console.log("aes key: " + aes_key_hex);
		console.log("aes iv: " + aes_iv_hex);

		var public_key = await getPublicKey();
		console.log("public key: " + public_key);

		encrypt = new JSEncrypt();
        console.log("created encrypt")
		encrypt.setPublicKey(public_key);
        console.log("public_key set")

		var aes_key_enc = encrypt.encrypt(aes_key_hex);
		console.log("aes key encrypted: " + aes_key_enc);

		return {
			data: dataEncBase64,
			key: aes_key_enc,
			iv: aes_iv_hex,
		};
	} catch {
		throw "Failed to encrypt data.";
	}
}

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
    //if (!/\d/.test(password)){
    //    alert("Senha deve conter ao menos um número");
    //    return;
    //}
    if (password.length < 8 || password.length > 20){
        alert("Senha deve ser maior que 8 caracteres e menor que 20");
        return;
    }
    var formDataJson = JSON.stringify(formData);
    var requestData = await encryptData(formDataJson);
	var requestDataJson = JSON.stringify(requestData);

    try {
        var response = await fetch("php/register.php", {
            method: "POST",
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
              },
            body: requestDataJson
        });

        if (response.ok) {
            var res = await response.text()
            console.log(res)
            alert("Cadastro feito com sucesso! Verifique sua caixa de entrada para verificação");
        } else {
            alert("Ocorreu um erro no envio!, tente novamente...");
        }
    } catch (error) {
        console.error("Error:", error);
    }
}