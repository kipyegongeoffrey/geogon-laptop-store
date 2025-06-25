<?php
session_start();
if (!isset($_SESSION['last_order_id'])) {
    echo "<h3>No recent order found.</h3>";
    exit;
}

$order_id = $_SESSION['last_order_id'];

$conn = new mysqli("localhost", "root", "", "geogon_store");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get order details
$orderQuery = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$orderQuery->bind_param("i", $order_id);
$orderQuery->execute();
$orderResult = $orderQuery->get_result();
$order = $orderResult->fetch_assoc();

// Get order items
$itemsQuery = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
$itemsQuery->bind_param("i", $order_id);
$itemsQuery->execute();
$itemsResult = $itemsQuery->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Receipt – Geogon Store</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f0f0;
            padding: 30px;
        }
        .receipt {
            background: white;
            padding: 30px;
            max-width: 700px;
            margin: auto;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            color: #004080;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        th {
            background: #004080;
            color: white;
        }
        .total {
            font-weight: bold;
            text-align: right;
        }
        .print-btn {
            margin-top: 20px;
            background: #004080;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            float: right;
        }
    </style>
</head>
<body>

<div class="receipt">
    <h2>🧾 Order Receipt</h2>
    <p><strong>Customer:</strong> <?= htmlspecialchars($order['name']) ?></p>
    <p><strong>Phone:</strong> <?= htmlspecialchars($order['phone']) ?></p>
    <p><strong>Location:</strong> <?= htmlspecialchars($order['location']) ?></p>
    <p><strong>Order Date:</strong> <?= $order['created_at'] ?></p>
    <p><strong>Status:</strong>
        <?= $order['fulfilled'] ? '<span style="color:green;">Fulfilled</span>' : '<span style="color:orange;">Pending</span>' ?>
    </p>

    <table>
        <tr>
            <th>Item</th>
            <th>Unit Price</th>
            <th>Qty</th>
            <th>Subtotal</th>
        </tr>
        <?php $total = 0; ?>
        <?php while ($item = $itemsResult->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($item['name']) ?></td>
                <td>KES <?= number_format($item['price']) ?></td>
                <td><?= $item['quantity'] ?></td>
                <td>KES <?= number_format($item['price'] * $item['quantity']) ?></td>
            </tr>
            <?php $total += $item['price'] * $item['quantity']; ?>
        <?php endwhile; ?>
        <tr>
            <td colspan="3" class="total">Total</td>
            <td><strong>KES <?= number_format($total) ?></strong></td>
        </tr>
    </table>

    <button onclick="window.print()" class="print-btn">🖨️ Print Receipt</button>
</div>

</body>
</html>