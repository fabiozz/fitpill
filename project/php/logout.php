<?php
session_start();

if (isset($_SESSION["user"])) {
    $_SESSION = array();

    session_destroy();

    echo "Logout successful";
} else {
    echo "No active session to logout";
}
?>
