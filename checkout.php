<?php
session_start();
include 'db.php';

if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
    header("Location: cart.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $location = $_POST['location'];

    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    // Insert order
    $stmt = $conn->prepare("INSERT INTO orders (name, phone, location, total) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssd", $name, $phone, $location, $total);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    // Insert order items
    $stmt_items = $conn->prepare("INSERT INTO order_items (order_id, product_name, quantity, subtotal) VALUES (?, ?, ?, ?)");
    foreach ($_SESSION['cart'] as $item) {
        $subtotal = $item['price'] * $item['quantity'];
        $stmt_items->bind_param("isid", $order_id, $item['name'], $item['quantity'], $subtotal);
        $stmt_items->execute();
    }

    $stmt->close();
    $stmt_items->close();

    // Save to session for thank you page
    $_SESSION['order'] = [
        'name' => $name,
        'phone' => $phone,
        'location' => $location,
        'cart' => $_SESSION['cart']
    ];

    $_SESSION['cart'] = [];

    header("Location: thankyou.php");
    exit();
}
?>

<!-- Include same HTML as previous checkout.php (form + order summary) -->
