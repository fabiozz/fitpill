<?php
    $path = '../certificate/certificate.crt';
    $certificate = file_get_contents($path);

    header('Content-Type: text/plain');
    echo $certificate;
?>