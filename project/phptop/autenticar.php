<?php
$secret = 'JFG2E74HV663OVETGIATQ6MN3QLMKD3IGTOPXN5JGWLCBGVKIRTWQE7K7QVVBANHXMQD73C37HLCI4Z6WWCVBFQZW5RAXE4VJQOXAYI'; 
$input = isset($_POST['otp']) ? $_POST['otp'] : null; 
use OTPHP\TOTP;

require 'vendor/autoload.php';

$resultado = ''; 

if ($input !== null) { 
    $otp = TOTP::create($secret);
    $check = $otp->verify($input);

    $resultado = $check ? 'Autenticado.' : 'Não autenticado.'; 
}
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
<body class=body>
    <div class="wrapper"> 
        <div class="loginform"> 
            <h1>Informe Token de autenticação de 2 fatores</h1>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST"> 
                <label for="otp"></label>
                <div class="input-box">
                <input type="text" id="otp" name="otp">
                <button class= button type="submit">Verificar</button>
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