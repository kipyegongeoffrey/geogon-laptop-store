<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "geogon_store");

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="fulfilled_orders.csv"');

$output = fopen('php://output', 'w');

// CSV column headers
fputcsv($output, ['Customer', 'Phone', 'Location', 'Amount', 'Order Time', 'M-PESA Receipt']);

$query = "
    SELECT 
        o.name AS customer_name,
        o.phone,
        o.location,
        o.amount,
        o.created_at AS order_time,
        p.mpesa_receipt
    FROM orders o
    LEFT JOIN payments p ON o.checkout_id = p.checkout_id
    WHERE o.paid = 1 AND o.fulfilled = 1
    ORDER BY o.created_at DESC
";

$result = $conn->query($query);

while ($row = $result->fetch_assoc()) {
    fputcsv($output, [
        $row['customer_name'],
        $row['phone'],
        $row['location'],
        $row['amount'],
        $row['order_time'],
        $row['mpesa_receipt']
    ]);
}

fclose($output);
exit();
?>