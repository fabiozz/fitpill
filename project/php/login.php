<?php
$user = $_POST['user'];
$password = $_POST['password'];

$mysqli = require __DIR__ . "/banco.php";

$sql = "SELECT * FROM usuarios WHERE usuario =? AND senha_usuario =?";

$stmt = $mysqli->prepare($sql);

$stmt->bind_param("ss", $user, $password);

$stmt->execute();

$stmt->store_result();

if ($stmt->num_rows > 0){
    echo "Login Sucedido!";
} else {
    die("Usuario ou senha inválidos!");
}

$stmt->close()

?>