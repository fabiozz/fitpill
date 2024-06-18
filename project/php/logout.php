<?php
session_start();

if (isset($_SESSION["user"])) {
    $_SESSION = array();

    session_destroy();

    echo "Saindo!";
} else {
    echo "Há algum erro na sessao!";
}
?>
