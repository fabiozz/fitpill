<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user'])) {
    echo json_encode(["error" => "Usuário não autenticado"]);
    exit;
}

$mysqli = require __DIR__ . "/banco.php";

$user = $_SESSION['user'];

// Consulta preparada para obter peso, altura e dias do usuário
$sql = "SELECT peso, altura, dias FROM usuarios WHERE usuario = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    
    $altura = $row['altura'];
    $peso = $row['peso'];
    $dias = $row['dias'];
    
    // Função para gerar o plano
    function gerarPlano($altura, $peso, $dias) {
        $calendario = [];
        $exercicios = ["Peito", "Costas", "Bíceps", "Tríceps", "Perna"];

        for ($i = 1; $i <= 7; $i++) {
            if ($i <= $dias) {
                $treino = $exercicios[array_rand($exercicios)];
            } else {
                $treino = "Descanso";
            }
            $calendario[] = ["dia" => $i, "treino" => $treino];
        }
        return $calendario;
    }

    $plano = gerarPlano($altura, $peso, $dias);

    echo json_encode($plano);
} else {
    echo json_encode(["error" => "Dados não encontrados"]);
}

$stmt->close();
?>
