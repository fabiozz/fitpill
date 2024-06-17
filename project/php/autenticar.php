<?php

session_start();

use OTPHP\TOTP;
require __DIR__ . '/../vendor/autoload.php';

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

        $input = $formData['otp'];
    }
} else {
    error_log('Erro nos dados!');
    echo 'Erro nos dados!';
    exit;
}

$user = $_SESSION['user'];

$sql = "SELECT segredo_2fa FROM usuarios WHERE usuario = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $user);

$stmt->execute();

$stmt->bind_result($secret);
$stmt->fetch();

$stmt->close();

if (!$secret) {
    $response = ['error' => 'O usuário não tem 2FA configurado.'];
} else {
    $otp = TOTP::create($secret);
    $check = $otp->verify($input);

    if ($otp->verify($input)) {
        $response = ['message' => 'Autenticado.'];
        $_SESSION["2FA"] = 1;
    } else {
        $response = ['error' => 'Não autenticado.'];
        $_SESSION["2FA"] = 0;
    }

}
echo json_encode($response);
?>