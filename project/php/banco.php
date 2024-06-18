<?php

$host = "localhost";
$dbname = "fitpill";
$username = "root";
$password = require __DIR__ . "/pass.php";

$mysqli = new mysqli(hostname: $host,
                     username: $username,
                     password: $password,
                     database: $dbname);
                     
if ($mysqli->connect_errno) {
    die("Connection error: " . $mysqli->connect_error);
}

return $mysqli;