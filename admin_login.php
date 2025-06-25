<?php
session_start();
$conn = new mysqli("localhost", "root", "", "geogon_store");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = hash('sha256', $_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM admin WHERE username=? AND password=?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $_SESSION['admin'] = $username;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid login credentials.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login – Geogon Store</title>
    <style>
        body { font-family: Arial; background: #eef; padding: 50px; }
        form { width: 300px; margin: auto; background: white; padding: 20px; border-radius: 8px; }
        input { width: 100%; padding: 10px; margin-bottom: 15px; }
        button { width: 100%; padding: 10px; background: #004080; color: white; border: none; }
        .error { color: red; text-align: center; }
    </style>
</head>
<body>

<form method="post">
    <h2>Admin Login</h2>
    <?php if (isset($error)): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
</form>

</body>
</html>
