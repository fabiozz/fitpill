<?php
## Validação de email por filtro do php
if ( ! filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    echo("Valid email is required");
}

## Validação de usuário e senha por regex
if ( preg_match("/[^A-Za-z0-9]/i", $_POST["user"])) {
    echo("Usuário não deve conter caracteres especiais");
}
if ( ! preg_match("/[A-Z]/i", $_POST["password"])) {
    echo("Senha deve conter uma letra maiúscula");
}
if ( ! preg_match("/[0-9]/", $_POST["password"])) {
    echo("Senha deve conter ao menos um número");
}

## Validação de tamanho de senha
if (strlen($_POST["password"]) < 8) {
    echo("Senha deve ter no mínimo 8 caracteres.");
}