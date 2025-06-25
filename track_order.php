<?php
$conn = new mysqli("localhost", "root", "", "geogon_store");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $phone = $_POST['phone'];
    $res = $conn->query("SELECT * FROM orders WHERE phone = '$phone' ORDER BY created_at DESC");
    echo "<h3>Orders for $phone:</h3><ul>";
    while ($row = $res->fetch_assoc()) {
        echo "<li>Order #{$row['id']} — KES {$row['amount']} — " . ($row['paid'] ? "✅ Paid" : "❌ Pending") . "</li>";
    }
    echo "</ul>";
}
?>

<form method="post">
    Enter your phone to track orders: <input name="phone" required>
    <input type="submit" value="Track">
</form>