<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "geogon_store");
$result = $conn->query("SELECT * FROM admins ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Admins – Geogon Store</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; padding: 20px; }
        h2 { color: #004080; text-align: center; }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            margin-top: 20px;
        }
        th, td { padding: 12px; border: 1px solid #ccc; text-align: center; }
        th { background: #004080; color: white; }
        a {
            color: red;
            text-decoration: none;
        }
    </style>
</head>
<body>

<h2>👤 Admin Users</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Created At</th>
        <th>Action</th>
    </tr>

    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['username']) ?></td>
            <td><?= $row['created_at'] ?></td>
            <td>
                <?php if ($row['username'] !== $_SESSION['admin']): ?>
                    <a href="delete_admin.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this admin?')">🗑 Delete</a>
                <?php else: ?>
                    (You)
                <?php endif; ?>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<p><a href="add_admin.php">➕ Add New Admin</a></p>
<p><a href="dashboard.php">⬅ Back to Dashboard</a></p>

</body>
</html>
