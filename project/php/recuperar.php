<?php
$token = $_POST["token"];
$token_hash = hash("sha256", $token);
$senha = $_POST["password"];

$mysqli = require __DIR__ . "/banco.php";

$sql = "SELECT * FROM usuarios
        WHERE troca_senha_token = ?";

$stmt = $mysqli->prepare($sql);

$stmt->bind_param("s", $token_hash);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

if ($user === null) {
    die("Usuário não encontrado");
}

$sql = "UPDATE usuarios
        SET senha_usuario = ?,
            troca_senha_token = NULL
        WHERE id = ?";

$stmt = $mysqli->prepare($sql);

$stmt->bind_param("ss", $senha, $user["id"]);

$stmt->execute();
