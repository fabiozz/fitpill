<?php
session_start();

$mysqli = require __DIR__ . "/banco.php";

if (!isset($_SESSION['AESKey'])) {
    echo 'Chave AES Não configurada!';
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['iv']) && isset($data['data'])) {
    $iv = hex2bin($data['iv']);
    $encryptedData = base64_decode($data['data']);
    $aes = hex2bin($_SESSION['AESKey']);

    $decryptedData = openssl_decrypt($encryptedData, 'AES-256-CBC', $aes, OPENSSL_RAW_DATA, $iv);

    if ($decryptedData === false) {
        error_log('Falha ao descriptografar!');
        echo 'Falha ao descriptografar!';
    } else {
        $formData = json_decode($decryptedData, true);

        $email = $formData['email'];
    }
} else {
    error_log('Erro nos dados!');
    echo 'Erro nos dados!';
    exit;
}
$token = bin2hex(random_bytes(16));

$token_hash = hash("sha256", $token);

$sql = "UPDATE usuarios
        SET troca_senha_token = ?
        WHERE email = ?";

$stmt = $mysqli->prepare($sql);

$stmt->bind_param("ss", $token_hash, $email);

$stmt->execute();

$mail = require __DIR__ . "/email.php";

$mail->setFrom("fitpill.services@gmail.com");
$mail->addAddress($email);
$mail->Subject = "Esqueceu sua senha? - FitPill";
$email = $email;
$mail->Body = <<<END

Esqueceu sua senha? Clique <a href="http://localhost/project/trocar_senha.html?token=$token">aqui</a> 
para registrar uma nova.

END;

try {
    $mail->send();
    $response['message'] = 'Email enviado! cheque sua caixa de entrada.';
} catch (Exception $e) {
    $response['error'] = 'Erro no envio do email';
}

echo json_encode($response);

?>