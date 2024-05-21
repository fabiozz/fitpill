<?php
session_start();
use OTPHP\TOTP;

require __DIR__ . '/../vendor/autoload.php'; 

if (isset($_POST["user"])) {
    $user = $_POST["user"];

    $host = "localhost";
    $dbname = "fitpill";
    $username = "root";
    $password = "";

    $mysqli = new mysqli($host, $username, $password, $dbname);

    if ($mysqli->connect_errno) {
        die("Connection error: " . $mysqli->connect_error);
    }

    $sql = "SELECT segredo_2fa FROM usuarios WHERE usuario = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $stmt->bind_result($db_secret);
    $stmt->fetch();
    $stmt->close();

    if (!$db_secret) {
        die("O usuario não tem 2FA configurado.");
    }

    $otp = TOTP::create($db_secret);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $submitted_code = $_POST["otp"];
        $is_valid = $otp->verify($submitted_code);

        if ($is_valid) {
            echo json_encode(['status' => 'success', 'message' => 'Autenticado.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Codigo incorreto.']);
        }
    }
    $mysqli->close();
}
?>
