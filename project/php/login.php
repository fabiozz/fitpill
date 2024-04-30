<?php


$mysqli = require __DIR__ . "/banco.php";

$user = $_POST['user'];
$password = $_POST['password'];

$sql = "SELECT * FROM usuarios
        WHERE usuario = ? AND senha_usuario = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ss",$user, $password);

$stmt->execute();

$result = $stmt->get_result();

$db_user = $result->fetch_assoc();

if ($db_user === NULL) {
    echo "teste";
    die($user);
}
else{
    session_start();
    $_SESSION["user"] = $user;
    $_SESSION["timeout"] = time();
    print_r($_SESSION);
    echo "Usuário válido";
}
