<?php
$conn = new mysqli("localhost", "root", "", "geogon_store");

header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="payments.csv"');

$output = fopen("php://output", "w");
fputcsv($output, ['ID', 'Receipt Number', 'Phone', 'Amount', 'Status', 'Payment Date']);

$result = $conn->query("SELECT * FROM payments ORDER BY created_at DESC");
while ($row = $result->fetch_assoc()) {
    fputcsv($output, [
        $row['id'],
        $row['mpesa_receipt_number'],
        $row['phone_number'],
        $row['amount'],
        $row['status'],
        $row['created_at']
    ]);
}
fclose($output);
exit();