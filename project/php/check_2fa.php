<?php
    if ($_SESSION['2FA'] == 1) {
        echo json_encode("True");
    } else {
        echo json_encode("False");
    }
?>