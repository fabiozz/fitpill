<?php
session_start(); // Inicie a sessão para gerenciar dados do usuário

require __DIR__ . '/../vendor/autoload.php';

$resultado = ''; // Variável para armazenar o resultado da autenticação

if (isset($_POST['otp'])) { // Verifique se o formulário foi enviado
  $otp = $_POST['otp']; // Obtenha o código OTP do formulário

  // Recupere o segredo do usuário do banco de dados (consulte o exemplo abaixo)
  $usuario = "fabio";
  $secret = $usuario['segredo_2fa'];

  $otp = TOTP::create($secret); // Crie um objeto TOTP com o segredo do usuário
  $check = $otp->verify($otp); // Verifique se o código OTP corresponde

  $resultado = $check ? 'Autenticado.' : 'Código OTP inválido.'; // Defina a mensagem de resultado
}

// Função para recuperar o segredo do usuário do banco de dados (exemplo):
function getSecret($usuario) {
  // Verifique se o parâmetro usuário é válido
  if (is_null($usuario) || empty($usuario)) {
    throw new InvalidArgumentException("O parâmetro usuário deve ser uma string não vazia.");
  }

  // Conecte-se ao banco de dados (consulte suas credenciais de acesso)
  $mysqli = new mysqli("localhost", "username", "password", "banco_de_dados");

  // Verifique se a conexão foi bem-sucedida
  if ($mysqli->connect_errno) {
    throw new RuntimeException("Falha ao conectar-se ao banco de dados: ({$mysqli->connect_errno}) {$mysqli->connect_error}");
  }

  // Prepare e execute a consulta para recuperar o segredo do usuário
  $sql = "SELECT segredo_2fa FROM usuarios WHERE usuario = ?";
  $stmt = $mysqli->prepare($sql);
  if ($stmt === false) {
    throw new RuntimeException("Falha ao preparar a consulta: ({$mysqli->errno}) {$mysqli->error}");
  }
  $stmt->bind_param("s", $usuario);
  $stmt->execute();
  if ($stmt->errno) {
    throw new RuntimeException("Falha ao executar a consulta: ({$stmt->errno}) {$stmt->error}");
  }
  $stmt->bind_result($segredo);
  $stmt->fetch();
  $stmt->close();

  // Verifique se o segredo foi recuperado
  if (is_null($segredo)) {
    throw new RuntimeException("O usuário '{$usuario}' não foi encontrado no banco de dados.");
  }

  $mysqli->close();

  return $segredo; // Retorne o segredo do usuário
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
<body class="body">
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
