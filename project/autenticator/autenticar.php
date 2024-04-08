<?php

require __DIR__ . '/vendor/autoload.php';
include_once('vendor/sonata-project/google-authenticator/src/FixedBitNotation.php');
include_once('vendor/sonata-project/google-authenticator/src/GoogleAuthenticator.php');
include_once('vendor/sonata-project/google-authenticator/src/GoogleQrUrl.php');
include_once('vendor/sonata-project/google-authenticator/src/RuntimeException.php');
include_once('vendor/sonata-project/google-authenticator/src/GoogleAuthenticatorInterface.php');

$g = new \Google\Authenticator\GoogleAuthenticator();

$secret = '';

if(isset($_POST['token'])){
    $token = $_POST['token'];
    if($g->checkCode($secret, $token)){
        echo 'Autenticação Liberada';
    }
    else{
        echo'Token invalido ou expirado';
    }
    die();
}
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
                <h1>Informe Token de autenticação de 2 fatores</h1>
                </form method="post">
                <input type="text" name="token"/>
                <button type="submit">Autenticar</button>
                </form>
            </div>
        </div>
    </body>


</html>