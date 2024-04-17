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

$user = $result->fetch_assoc();

if ($user === null) {
    die($user);
}
else{
    echo "Usuário válido";
}

?>