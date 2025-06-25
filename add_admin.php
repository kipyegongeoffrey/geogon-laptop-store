<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $hash = hash('sha256', $password);

    $conn = new mysqli("localhost", "root", "", "geogon_store");

    $stmt = $conn->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hash);

    if ($stmt->execute()) {
        $msg = "✅ New admin added successfully.";
    } else {
        $msg = "❌ Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Admin – Geogon Store</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; padding: 20px; }
        form { background: white; padding: 20px; max-width: 400px; margin: auto; border-radius: 8px; }
        input[type="text"], input[type="password"] {
            width: 100%; padding: 10px; margin: 10px 0;
            border: 1px solid #ccc; border-radius: 5px;
        }
        input[type="submit"] {
            padding: 10px 20px; background: #004080;
            color: white; border: none; border-radius: 5px;
        }
        .msg { text-align: center; margin-top: 10px; color: green; }
        a { display: block; text-align: center; margin-top: 20px; color: #004080; text-decoration: none; }
    </style>
</head>
<body>

<h2 style="text-align:center;">➕ Add New Admin</h2>

<form method="post">
    <input type="text" name="username" placeholder="Admin username" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="submit" value="Add Admin">
</form>

<?php if ($msg): ?>
    <div class="msg"><?= $msg ?></div>
<?php endif; ?>

<a href="view_admins.php">⬅ Back to Admin List</a>

</body>
</html>