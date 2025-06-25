<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $conn = new mysqli("localhost", "root", "", "geogon_store");

    // Prevent deleting yourself
    $stmt = $conn->prepare("SELECT username FROM admins WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($usernameToDelete);
    $stmt->fetch();
    $stmt->close();

    if ($usernameToDelete !== $_SESSION['admin']) {
        $stmt = $conn->prepare("DELETE FROM admins WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

    header("Location: view_admins.php");
    exit();
}