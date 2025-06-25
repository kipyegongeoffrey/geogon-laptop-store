<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "geogon_store");
$result = $conn->query("SELECT * FROM payments ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Payments – Geogon Store</title>
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

<h2>💰 M-PESA Payments</h2>

<table>
    <tr>
        <th>#</th>
        <th>Receipt</th>
        <th>Phone</th>
        <th>Amount</th>
        <th>Status</th>
        <th>Date</th>
    </tr>

    <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['mpesa_receipt_number']) ?></td>
            <td><?= $row['phone_number'] ?></td>
            <td>KES <?= number_format($row['amount'], 2) ?></td>
            <td><?= $row['status'] ?></td>
            <td><?= $row['created_at'] ?></td>
        </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="6">No payments received yet.</td></tr>
    <?php endif; ?>

</table>

<a href="dashboard.php" class="back">⬅ Back to Dashboard</a>

</body>
</html>
