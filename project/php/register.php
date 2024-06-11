<?php

$response = new stdClass();

function print_json($content) {
  echo json_encode($content, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
}

$request = json_decode(file_get_contents('php://input'));

if (
    !isset($request->data) or
    !isset($request->key) or
    !isset($request->iv)
  ) {
    $response->error = true;
    $response->msg = "Not all parameters were passed.";
  
    print_json($response);
    print_json($request);
    die();
  }

$private_key = file_get_contents("private.pem");

if (!$private_key) {
    $response->error = true;
    $response->msg = "private.pem file not found.";
    print_json($private_key);
    print_json($response);
    die();
  }
  
$key_dec = null;

$rs = openssl_private_decrypt(
  base64_decode($request->key),
  $key_dec,
  $private_key,
);

if (!$rs) {
  $response->error = true;
  $response->msg = "Unable to decrypt key with RSA.";

  print_json($response);
  die();
}

$data_dec = openssl_decrypt(
  base64_decode($request->data),
  "aes-256-cbc",
  hex2bin($key_dec),
  OPENSSL_RAW_DATA,
  hex2bin($request->iv)
);

if (!$data_dec) {
    $response->error = true;
    $response->msg = "Unable to decrypt data with AES.";

    print_json($response);
    die();
  }

$data = json_decode($data_dec);

$email_token = bin2hex(random_bytes(16));

$email_token_hash = hash("sha256", $email_token);

$mysqli = require __DIR__ . "/banco.php";

$sql = "INSERT INTO `usuarios` (usuario, email, senha_usuario, email_token)
        VALUES (?, ?, ?, ?)";

$stmt = $mysqli->stmt_init();
$stmt->prepare($sql);
$stmt->bind_param("ssss",
                $data->user,
                $data->email,
                $data->password,
                $email_token_hash);

if ($stmt->execute()) {

    $mail = require __DIR__ . "/email.php";

    $mail->setFrom("fitpill.services@gmail.com");
    $mail->addAddress($data->email);
    $mail->Subject = "Ative sua conta - FitPill";
    $mail->Body = <<<END

    Bem vindo ao Fitpill! Clique <a href="http://localhost/project/php/ativa_conta.php?token=$email_token">aqui</a> 
    para ativar sua conta.

    END;

    try {
        $mail->send();
    } catch (Exception $e) {
        echo json_encode("Email não enviado. Erro: {$mail->ErrorInfo}");
    }
} else {
    
    if ($mysqli->errno === 1062) {
        echo json_encode("Email já cadastrado.");
    } else {
        echo json_encode($mysqli->error . " " . $mysqli->errno);
    }
}