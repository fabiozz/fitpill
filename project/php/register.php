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
      $email = $formData['email'];
      $password = $formData['password'];
  }
} else {
  error_log('Erro nos dados!');
  echo 'Erro nos dados!';
  exit;
}

$email_token = bin2hex(random_bytes(16));
$email_token_hash = hash("sha256", $email_token);

$sql = "INSERT INTO `usuarios` (usuario, email, senha_usuario, email_token)
        VALUES (?, ?, ?, ?)";

$stmt = $mysqli->stmt_init();
$stmt->prepare($sql);
$stmt->bind_param("ssss",
                $user,
                $email,
                $password,
                $email_token_hash);

if ($stmt->execute()) {

    $mail = require __DIR__ . "/email.php";

    $mail->setFrom("fitpill.services@gmail.com");
    $mail->addAddress($email);
    $mail->Subject = "Ative sua conta - FitPill";
    $mail->Body = <<<END

    Bem vindo ao Fitpill! Clique <a href="http://localhost/project/php/ativa_conta.php?token=$email_token">aqui</a> 
    para ativar sua conta.

    END;

    try {
      $mail->send();
      $response['message'] = 'Cadastro feito com sucesso! Verifique seu email para ativar sua conta.';
    } catch (Exception $e) {
      $response['error'] = "Email não enviado. Erro: {$mail->ErrorInfo}";
    }
} else {
    if ($mysqli->errno === 1062) {
      $response['error'] = "Email já cadastrado.";
    } else {
      $response['error'] = $mysqli->error . " " . $mysqli->errno;
    }
}
echo json_encode($response)
?>