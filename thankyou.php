<?php
session_start();
$order = isset($_SESSION['order']) ? $_SESSION['order'] : null;
unset($_SESSION['order']); // Clear after use

if (!$order) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Complete – Geogon Store</title>
    <style>
        body { font-family: Arial; background: #f9f9f9; padding: 20px; }
        .box {
            max-width: 700px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 { color: green; }
        table {
            margin: 20px auto;
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        .info { text-align: left; margin-top: 20px; }
        nav {
            background: #004080;
            padding: 10px;
            text-align: center;
        }
        nav a {
            color: white;
            margin: 0 10px;
            font-weight: bold;
            text-decoration: none;
        }
    </style>
</head>
<body>

<nav>
    <a href="index.php">Home</a>
    <a href="products.php">Products</a>
</nav>

<div class="box">
    <h2>✅ Thank You for Your Order!</h2>
    <p>We will contact you shortly for delivery.</p>

    <div class="info">
        <p><strong>Name:</strong> <?php echo htmlspecialchars($order['name']); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['phone']); ?></p>
        <p><strong>Location:</strong> <?php echo htmlspecialchars($order['location']); ?></p>
    </div>

    <h3>Order Summary</h3>
    <table>
        <tr><th>Product</th><th>Qty</th><th>Subtotal</th></tr>
        <?php
        $total = 0;
        foreach ($order['cart'] as $item):
            $subtotal = $item['price'] * $item['quantity'];
            $total += $subtotal;
        ?>
        <tr>
            <td><?php echo htmlspecialchars($item['name']); ?></td>
            <td><?php echo $item['quantity']; ?></td>
            <td><?php echo number_format($subtotal); ?></td>
        </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="2"><strong>Total</strong></td>
            <td><strong>KES <?php echo number_format($total); ?></strong></td>
        </tr>
    </table>
</div>

</body>
</html>
