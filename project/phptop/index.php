<?php
session_start();
use OTPHP\TOTP;

require __DIR__ . '/../vendor/autoload.php'; 

$host = "localhost";
$dbname = "fitpill";
$username = "root";
$password = "";

$mysqli = new mysqli($host, $username, $password, $dbname);

if ($mysqli->connect_errno) {
    die("Connection error: " . $mysqli->connect_error);
}

$sql_last_user = "SELECT usuario FROM usuarios ORDER BY id DESC LIMIT 1";
$result_last_user = $mysqli->query($sql_last_user);

if ($result_last_user->num_rows > 0) {
    $row_last_user = $result_last_user->fetch_assoc();
    $usuario = $row_last_user['usuario'];
} else {
    echo "Nenhum usuário encontrado no banco de dados.";
}

function generateSecret(string $usuario): string
{
    $googleAuthenticator = new PHPGangsta_GoogleAuthenticator();
    $secret = $googleAuthenticator->createSecret();
    $label = $usuario . '@fitpill.com';
    if ($label === null) {
        throw new \UnexpectedValueException('Unexpected null value for label');
    }
    $url = $googleAuthenticator->getQRCodeGoogleUrl($label, $secret);
    if ($url === null) {
        throw new \UnexpectedValueException('Unexpected null value for URL');
    }
    return $secret;
}

$secret = generateSecret($usuario);

$otp = TOTP::create($secret);
$otp->setLabel($usuario . '@fitpill.com');

$grCodeUri = $otp->getQrCodeUri(
    'https://api.qrserver.com/v1/create-qr-code/?data=[DATA]&size=300x300&ecc=M',
    '[DATA]'
);


$sql = "UPDATE usuarios SET segredo_2fa = ? WHERE usuario = ?";
$stmt = $mysqli->prepare($sql);

// Verifica se a preparação da declaração foi bem-sucedida
if ($stmt) {
    $stmt->bind_param("ss", $secret, $usuario);
    $stmt->execute();
    $stmt->close();
} else {
    // Lidar com o erro de preparação da declaração
    echo "Erro na preparação da declaração: " . $mysqli->error;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/autenticar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Register Authenticator</title>
</head>
<body class="body">
<div class="wrapper">
    <div class="loginform">
        <h1>Autenticação de dois Fatores</h1>
        <p>Escaneie para ativar a autenticação de dois fatores:</p>
        <div class="qrcode" style="border: 1px solid #ccc; padding: 10px;">
            <img src="<?php echo $grCodeUri; ?>" alt="QR Code" style="max-width: 100%;">   </div>
        </form>
    </div>
</div>
</body>
</html>