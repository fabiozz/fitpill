<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/autenticar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Register Authenticator</title>
</head>
<body class=body>
    <div class="wrapper"> 
    <div class="loginform">
    <h1>Metódo Autenticação de 2 fatores</h1>
    <div class="php">
    <?php
    use OTPHP\TOTP;

    require __DIR__ . '/../vendor/autoload.php';

    // Secret already provided
    $secret ='JFG2E74HV663OVETGIATQ6MN3QLMKD3IGTOPXN5JGWLCBGVKIRTWQE7K7QVVBANHXMQD73C37HLCI4Z6WWCVBFQZW5RAXE4VJQOXAYI';


    $otp = TOTP::create($secret);

    // Note: You must set label before generating the QR code
    $otp->setLabel('user@fitpill');
    $grCodeUri = $otp->getQrCodeUri(
        'https://api.qrserver.com/v1/create-qr-code/?data=' . urlencode($secret) . '&size=300x300&ecc=M',
        $secret
    );
    echo "<img src='{$grCodeUri}' alt='QR Code'>";
    ?>
    </div>
    </div>
</div>
</body>
</html>