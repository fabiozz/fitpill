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
    $user = $row_last_user['usuario'];
} else {
    echo "Nenhum usuário encontrado no banco de dados.";
}

function generateSecret(string $user): string
{
    $googleAuthenticator = new PHPGangsta_GoogleAuthenticator();
    $secret = $googleAuthenticator->createSecret();
    $label = $user . '@fitpill.com';
    if ($label === null) {
        throw new \UnexpectedValueException('Unexpected null value for label');
    }
    $url = $googleAuthenticator->getQRCodeGoogleUrl($label, $secret);
    if ($url === null) {
        throw new \UnexpectedValueException('Unexpected null value for URL');
    }
    return $secret;
}

$secret = generateSecret($user);

$otp = TOTP::create($secret);
$otp->setLabel($user . '@fitpill.com');

$grCodeUri = $otp->getQrCodeUri(
    'https://api.qrserver.com/v1/create-qr-code/?data=[DATA]&size=300x300&ecc=M',
    '[DATA]'
);


$sql = "UPDATE usuarios SET segredo_2fa = ? WHERE usuario = ?";
$stmt = $mysqli->prepare($sql);

if ($stmt) {
    $stmt->bind_param("ss", $secret, $user);
    $stmt->execute();
    $stmt->close();
} else {
    echo "Erro na preparação da declaração: " . $mysqli->error;
}

$qrCodeData = $grCodeUri;

$responseData = array(
    "qrCodeData" => $qrCodeData
  );
  
  header('Content-Type: application/json');
  echo json_encode($responseData); 
  
?>