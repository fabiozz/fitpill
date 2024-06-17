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

        $user = $formData['user'];
        $password = $formData['password'];
    }
} else {
    error_log('Erro nos dados!');
    echo 'Erro nos dados!';
    exit;
}

$sql = "SELECT * FROM usuarios
        WHERE usuario = ? AND senha_usuario = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ss",$user, $password);

$stmt->execute();

$result = $stmt->get_result();

$db_user = $result->fetch_assoc();

if ($db_user === NULL) {
    $response['error'] = 'Usuário ou senha inválidos';
} else{
    $_SESSION["user"] = $user;
    $_SESSION["2FA"] = 0;
    $response['message'] = 'Usuário válido';
}

echo json_encode($response)
?>