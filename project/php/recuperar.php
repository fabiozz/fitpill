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

        $senha = $formData['password'];
        $token = $formData['token'];
    }
} else {
    error_log('Erro nos dados!');
    echo 'Erro nos dados!';
    exit;
}

$token_hash = hash("sha256", $token);

$sql = "SELECT * FROM usuarios
        WHERE troca_senha_token = ?";

$stmt = $mysqli->prepare($sql);

$stmt->bind_param("s", $token_hash);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

if ($user === null) {
    $response['error'] = 'Usuário nao existe!';
} else {
    $sql = "UPDATE usuarios
        SET senha_usuario = ?,
            troca_senha_token = NULL
        WHERE id = ?";

    $stmt = $mysqli->prepare($sql);

    $stmt->bind_param("ss", $senha, $user["id"]);

    if ($stmt->execute()) {
        $response['message'] = 'Senha atualizada com sucesso!';
    } else{
        $response['error'] = 'Erro ao atualizar a senha no banco de dados.';
    }
}

echo json_encode($response);

?>

