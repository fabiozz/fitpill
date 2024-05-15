<?php
session_start();
if (isset($_SESSION["user"])) {
    echo "Bem vindo, " . htmlspecialchars($_SESSION['user']) . "!";
}
else{
    die("not_log");
}

if (time() - $_SESSION["timeout"] > 15 * 60){
    session_destroy();
    die("session_to");
}