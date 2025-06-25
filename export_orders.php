<?php
$conn = new mysqli("localhost", "root", "", "geogon_store");

header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="orders.csv"');

$output = fopen("php://output", "w");
fputcsv($output, ['ID', 'Customer Name', 'Phone', 'Location', 'Amount', 'Status', 'Order Date']);

$result = $conn->query("SELECT * FROM orders ORDER BY order_date DESC");
while ($row = $result->fetch_assoc()) {
    fputcsv($output, [
        $row['id'],
        $row['customer_name'],
        $row['phone'],
        $row['location'],
        $row['amount'],
        $row['status'],
        $row['order_date']
    ]);
}
fclose($output);
exit();