<?php
session_start();
if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "geogon_store");
$cid = $_SESSION['customer_id'];

$result = $conn->query("SELECT * FROM orders WHERE customer_id = $cid ORDER BY created_at DESC");
?>

<h2>Welcome, <?= $_SESSION['customer_name'] ?>!</h2>
<a href="logout.php">Logout</a>

<h3>Your Orders:</h3>
<table border="1" cellpadding="10">
    <tr><th>ID</th><th>Amount</th><th>Status</th><th>Date</th></tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td>KES <?= number_format($row['amount']) ?></td>
        <td><?= $row['paid'] ? 'Paid' : 'Pending' ?></td>
        <td><?= $row['created_at'] ?></td>
    </tr>
    <?php endwhile; ?>
</table>