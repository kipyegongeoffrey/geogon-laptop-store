<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "geogon_store");

$query = "
    SELECT 
        o.id,
        o.name AS customer_name,
        o.phone,
        o.location,
        o.amount,
        o.paid,
        o.fulfilled,
        o.created_at AS order_time,
        p.mpesa_receipt,
        p.created_at AS payment_time
    FROM orders o
    LEFT JOIN payments p ON o.checkout_id = p.checkout_id
    ORDER BY o.created_at DESC
";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Matched Orders & Payments – Geogon Store</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; padding: 20px; }
        h2 { color: #004080; }
        table {
            width: 100%; background: white; border-collapse: collapse;
        }
        th, td {
            padding: 10px; border: 1px solid #ccc; text-align: center;
        }
        th { background: #004080; color: white; }
        tr:nth-child(even) { background: #f9f9f9; }
        .paid { color: green; font-weight: bold; }
        .unpaid { color: red; font-weight: bold; }
        .fulfilled { color: green; font-weight: bold; }
        .unfulfilled { color: red; font-weight: bold; }
        a.mark-btn { color: #007bff; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

<h2>📦 Matched Orders & M-PESA Payments</h2>

<table>
    <tr>
        <th>Customer</th>
        <th>Phone</th>
        <th>Location</th>
        <th>Amount</th>
        <th>Status</th>
        <th>MPESA Receipt</th>
        <th>Order Time</th>
        <th>Payment Time</th>
        <th>Fulfilled</th>
        <th>Action</th>
    </tr>

    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['customer_name']) ?></td>
            <td><?= htmlspecialchars($row['phone']) ?></td>
            <td><?= htmlspecialchars($row['location']) ?></td>
            <td>KES <?= number_format($row['amount']) ?></td>
            <td>
                <?= $row['paid'] ? '<span class="paid">✓ Paid</span>' : '<span class="unpaid">✗ Unpaid</span>' ?>
            </td>
            <td><?= $row['mpesa_receipt'] ?? '—' ?></td>
            <td><?= $row['order_time'] ?></td>
            <td><?= $row['payment_time'] ?? '—' ?></td>
            <td>
                <?= $row['fulfilled'] ? '<span class="fulfilled">✔</span>' : '<span class="unfulfilled">✘</span>' ?>
            </td>
            <td>
                <?php if (!$row['fulfilled'] && $row['paid']): ?>
                    <a class="mark-btn" href="mark_fulfilled.php?id=<?= $row['id'] ?>" onclick="return confirm('Mark this order as fulfilled?')">Mark Fulfilled</a>
                <?php else: ?>
                    —
                <?php endif; ?>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

</body>
</html>