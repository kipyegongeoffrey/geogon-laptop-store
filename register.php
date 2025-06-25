<?php
$conn = new mysqli("localhost", "root", "", "geogon_store");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name  = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $pass  = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO customers (name, email, phone, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $phone, $pass);
    $stmt->execute();
    echo "✅ Registration successful. <a href='login.php'>Login</a>";
}
?>
<form method="post">
    Name: <input name="name" required><br>
    Email: <input name="email" required><br>
    Phone: <input name="phone" required><br>
    Password: <input type="password" name="password" required><br>
    <input type="submit" value="Register">
</form>
