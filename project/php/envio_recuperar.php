<?php
$token = bin2hex(random_bytes(16));

$token_hash = hash("sha256", $token);

$mysqli = require __DIR__ . "/banco.php";

$sql = "UPDATE usuarios
        SET troca_senha_token = ?
        WHERE email = ?";

$stmt = $mysqli->prepare($sql);

$stmt->bind_param("ss", $token_hash, $_POST["email"]);

$stmt->execute();

$mail = require __DIR__ . "/email.php";

$mail->setFrom("fitpill.services@gmail.com");
$mail->addAddress($_POST["email"]);
$mail->Subject = "Esqueceu sua senha? - FitPill";
$email = $_POST["email"];
$mail->Body = <<<END

Esqueceu sua senha? Clique <a href="http://localhost/project/trocar_senha.html?token=$token">aqui</a> 
para registrar uma nova.

END;

try {
    $mail->send();
} catch (Exception $e) {
    echo json_encode("Email não enviado. Erro: {$mail->ErrorInfo}");
}