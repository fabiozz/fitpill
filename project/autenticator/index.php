<?php

require __DIR__ . '/vendor/autoload.php';
include_once('vendor/sonata-project/google-authenticator/src/FixedBitNotation.php');
include_once('vendor/sonata-project/google-authenticator/src/GoogleAuthenticator.php');
include_once('vendor/sonata-project/google-authenticator/src/GoogleQrUrl.php');
include_once('vendor/sonata-project/google-authenticator/src/RuntimeException.php');
include_once('vendor/sonata-project/google-authenticator/src/GoogleAuthenticatorInterface.php');

$g = new \Google\Authenticator\GoogleAuthenticator();

$secret = 'XVQ2UIGO75XRUKJO';
?>

<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <title>Google Auth</title>
        <link rel="stylesheet" href="../css/login.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>
    <body class="body">

        <div class="wrapper">
            <div class="loginform">
                <h1>Registre a autenticação de 2 fatores</h1>
                <img src="<?php echo $g ->getUrl('user','fitpill.com', $secret) ?>" />
            </div>
        </div>
    </body>


</html>