<?php

$token = $_GET["token"];

$token_hash = hash("sha256", $token);

$mysqli = require __DIR__ . "/banco.php";

$sql = "SELECT * FROM user
        WHERE email_token = ?";

$stmt = $mysqli->prepare($sql);

$stmt->bind_param("s", $token_hash);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

if ($user === null) {
    die("token not found");
}

$sql = "UPDATE user
        SET account_activation_hash = NULL
        WHERE id = ?";

$stmt = $mysqli->prepare($sql);

$stmt->bind_param("s", $user["id"]);

$stmt->execute();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Conta ativada</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>

    <h1>Conta ativada!</h1>

    <p>Sua conta foi ativada com sucesso. Siga com seu
       <a href="../login.html">login</a>.</p>

</body>
</html>