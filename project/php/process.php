<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $altura = $_POST['altura'];
    $peso = $_POST['peso'];
    $dias = $_POST['dias'];

    function gerarPlano($altura, $peso, $dias) {
        $calendario = [];
        $exercicios = ["Cardio", "Musculação", "Yoga", "Pilates", "HIIT"];
        $dietas = ["Proteína alta", "Carboidrato moderado", "Baixa gordura", "Vegetariana"];

        for ($i = 1; $i <= 7; $i++) {
            if ($i <= $dias) {
                $treino = $exercicios[array_rand($exercicios)];
                $dieta = $dietas[array_rand($dietas)];
            } else {
                $treino = "Descanso";
                $dieta = $dietas[array_rand($dietas)];
            }
            $calendario[] = ["dia" => $i, "treino" => $treino, "dieta" => $dieta];
        }
        return $calendario;
    }

    $plano = gerarPlano($altura, $peso, $dias);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/home1.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Seu Plano de Treino e Dieta</title>
</head>
<body>
    <nav>
        <div class="logo">
            <img src="img/fitpill.png" alt="logo" />
            <h1>Fitpill</h1>
        </div>
        <ul>
            <li><a href="../home.html">Home</a></li>
            <li><a href="#">Services</a></li>
            <li><a href="#">Blog</a></li>
            <li><a href="#">Contact Us</a></li>
        </ul>
        <div class="hamburger">
            <span class="line"></span>
            <span class="line"></span>
            <span class="line"></span>
        </div>
    </nav>
    <div class="menubar">
        <ul>
            <li><a href="home.html">Home</a></li>
            <li><a href="#">Services</a></li>
            <li><a href="#">Blog</a></li>
            <li><a href="#">Contact Us</a></li>
        </ul>
    </div>
    <section class="register">
        <div class="register-card">
            <h1>Seu Plano de Treino e Dieta</h1>
            <table border="1">
                <tr>
                    <th>Dia</th>
                    <th>Treino</th>
                    <th>Dieta</th>
                </tr>
                <?php foreach ($plano as $dia): ?>
                    <tr>
                        <td><?php echo $dia["dia"]; ?></td>
                        <td><?php echo $dia["treino"]; ?></td>
                        <td><?php echo $dia["dieta"]; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </section>
</body>
</html>
