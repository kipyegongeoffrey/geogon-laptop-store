<?php
session_start();
$conn = new mysqli("localhost", "root", "", "geogon_store");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $pass  = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, name, password FROM customers WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();
    $user = $res->fetch_assoc();

    if ($user && password_verify($pass, $user['password'])) {
        $_SESSION['customer_id'] = $user['id'];
        $_SESSION['customer_name'] = $user['name'];
        header("Location: dashboard.php");
        exit();
    } else {
        echo "❌ Invalid login!";
    }
}
?>
<form method="post">
    Email: <input name="email" required><br>
    Password: <input type="password" name="password" required><br>
    <input type="submit" value="Login">
</form>