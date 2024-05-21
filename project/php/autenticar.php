<?php
use OTPHP\TOTP;
require _DIR_ . '/../vendor/autoload.php';

$resultado = '';

if (isset($_POST['user'])) {
    $host = "localhost";
    $dbname = "fitpill";
    $username = "root";
    $password = "";

    $mysqli = new mysqli($host, $username, $password, $dbname);

    if ($mysqli->connect_errno) {
        $resultado = 'Erro de conexão com o banco de dados: ' . $mysqli->connect_error;
    } else {
        $user = $_POST['user'];
        $sql = "SELECT segredo_2fa FROM usuarios WHERE usuario = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $stmt->bind_result($secret);
        $stmt->fetch();
        $stmt->close();

        if (!$secret) {
            $resultado = 'O usuário não tem 2FA configurado.';
        } else {
            $input = isset($_POST['otp']) ? $_POST['otp'] : null;

            if ($input !== null) {
                $otp = TOTP::create($secret);
                $check = $otp->verify($input);

                $resultado = $check ? 'Autenticado.' : 'Não autenticado.';
            }
        }

        $mysqli->close();
    }
} else {
    $resultado = 'Parâmetro de usuário ausente.';
}

echo $resultado;
?>