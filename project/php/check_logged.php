<?php
if (time() - $_SESSION["timeout"] > 30 * 60){
    //session_destroy();
    die(var_dump($_SESSION));
}
if (isset($_SESSION["user"])) {
    echo "Bem vindo, " . htmlspecialchars($_SESSION['user']) . "!";
}
else{
    die($var_dump($_SESSION));
}