<?php
// Purpose: Database connection for all PHP files
$conn = new mysqli("localhost", "root", "", "event_management");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

