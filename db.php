<?php
// db.php – handles MySQL database connection
$host = "localhost";
$user = "root";
$password = "";
$database = "geogon_store";

$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
