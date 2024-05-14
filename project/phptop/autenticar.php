<?php
session_start();
use OTPHP\TOTP;

require __DIR__ . '/../vendor/autoload.php'; 

$usuario = $_POST["usuario"];;

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
$stmt->bind_param("s", $usuario);
$stmt->execute();
$stmt->bind_result($db_secret);
$stmt->fetch();
$stmt->close();

if (!$db_secret) {
    die("O usuario não tem 2FA configurado.");
}

$otp = TOTP::create($db_secret);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $submitted_code = $_POST["codigo_2fa"];
    $is_valid = $otp->verify($submitted_code);

    if ($is_valid) {
        echo "Autenticado.";
    } else {
        echo "Codigo incorreto.";
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/autenticar.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Authenticator</title>
</head>
<body>
    <div class="wrapper"> 
        <div class="loginform"> 
            <h1>Informe Token de autenticação de 2 fatores</h1>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST"> 
                <label for="otp"></label>
                <div class="input-box">
                <input type="text" id="otp" name="otp">
                <button class="button" type="submit">Verificar</button>
                <i class="fa fa-envelope"></i>
                </div>
            </form>
            <br>
            <br>
            <div class="resultado">
            <?php if ($resultado !== '') : ?>
                <p><?php echo $resultado; ?></p> 
            <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>