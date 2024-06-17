<?php
    session_start();
    if (isset($_SESSION["user"])) {
        if (isset ($_SESSION['timeout']) && (time() - $_SESSION['timeout'] > 15 * 60)) {
            session_unset();
            session_destroy();
            exit;
        } else {
            $_SESSION['timeout'] = time();
        }
        echo json_encode("True");
    } else{
        echo json_encode("False");
    }
?>