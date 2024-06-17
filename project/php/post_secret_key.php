<?php
    session_start();

    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['aes'])) {
        $encryptedAES = $data['aes'];

        $privateKeyPem = file_get_contents('../certificate/private.key');
        $privateKey = openssl_pkey_get_private($privateKeyPem);

        if (openssl_private_decrypt(base64_decode($encryptedAES), $decryptedAES, $privateKey)) {
            $_SESSION['AESKey'] = $decryptedAES;
            error_log('Chave AES armazenada na sessão!');
            echo 'Chave AES recebida e armazenada!';
        } else {
            error_log('Não foi possível decifrar a chave AES!');
            echo 'Falha ao descriptografar a chave AES!';
        }
    } else {
        error_log('Nenhuma chave recebida!');
        echo 'Nenhuma chave recebida';
    }
?>