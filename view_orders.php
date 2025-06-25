<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "geogon_store");
$result = $conn->query("SELECT * FROM orders ORDER BY order_date DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Orders – Geogon Store</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        h2 { text-align: center; color: #004080; }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: center;
        }
        th {
            background: #004080;
            color: white;
        }
        a.back {
            display: inline-block;
            margin-top: 20px;
            color: #004080;
            text-decoration: none;
        }
    </style>
</head>
<body>

<h2>📦 Customer Orders</h2>

<table>
    <tr>
        <th>#</th>
        <th>Customer Name</th>
        <th>Phone</th>
        <th>Location</th>
        <th>Amount</th>
        <th>Date</th>
    </tr>

    <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['customer_name']) ?></td>
            <td><?= $row['phone'] ?></td>
            <td><?= htmlspecialchars($row['location']) ?></td>
            <td>KES <?= number_format($row['amount'], 2) ?></td>
            <td><?= $row['order_date'] ?></td>
        </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="6">No orders yet.</td></tr>
    <?php endif; ?>

</table>

<a href="dashboard.php" class="back">⬅ Back to Dashboard</a>

</body>
</html>