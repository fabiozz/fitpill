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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/login.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Authenticator</title>
</head>
<body>
    <section>
        <div class="image-box">
            <img src="../img/arnold.jpg">
        </div>
        <div class="content-box">
            <div class="form-box">
                <h2>Informe Token de autenticação de 2 fatores</h2>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST"> 
                    <div class="input-box">
                        <label for="otp">Token:</label>
                        <input type="text" id="otp" name="otp" required>
                        <i class="fa fa-lock"></i>
                    </div>
                    <div class="input-box">
                        <button class="button" type="submit">Verificar</button>
                    </div>
                </form>
                <br>
                <br>
                <div class="resultado">
                    <?php if (isset($resultado) && $resultado !== '') : ?>
                        <p><?php echo $resultado; ?></p> 
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
