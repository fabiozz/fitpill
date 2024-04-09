<?php
$email_token = bin2hex(random_bytes(16));

$email_token_hash = hash("sha256", $email_token);

$mysqli = require __DIR__ . "/banco.php";

$sql = "INSERT INTO `usuarios` (usuario, email, senha_usuario, email_token)
        VALUES (?, ?, ?, ?)";

$stmt = $mysqli->stmt_init();
$stmt->prepare($sql);
$stmt->bind_param("ssss",
                $_POST["user"],
                $_POST["email"],
                $_POST["password"],
                $email_token_hash);

if ($stmt->execute()) {

    $mail = require __DIR__ . "/email.php";

    $mail->setFrom("fitpill.services@gmail.com");
    $mail->addAddress($_POST["email"]);
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