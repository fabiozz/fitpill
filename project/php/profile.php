<?php
session_start();

// Include your database connection
$mysqli = require __DIR__ . "/banco.php";

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    echo json_encode(array()); // Return empty JSON if user is not logged in
    exit;
}

$user = $_SESSION['user'];

// Prepare SQL statement with a prepared statement
$sql = "SELECT usuario, email, altura, peso, dias FROM usuarios WHERE usuario = ?";
$stmt = $mysqli->prepare($sql);

if (!$stmt) {
    // Handle SQL prepare error
    echo json_encode(array()); // Return empty JSON on error
    exit;
}

// Bind parameter and execute query
$stmt->bind_param("s", $user); // Assuming 'usuario' is a string (varchar)
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode($row); // Encode fetched data as JSON and echo
} else {
    echo json_encode(array()); // Return empty JSON if no data found
}

$stmt->close(); // Close statement
?>
