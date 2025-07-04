<?php
// confirmation.php – Receives M-PESA callback from Safaricom
header("Content-Type: application/json");

// 1. Get raw response data
$data = file_get_contents("php://input");
$logFile = "mpesa_responses.txt";

// 2. Save full response to log file (for debugging)
file_put_contents($logFile, $data . PHP_EOL, FILE_APPEND);

// 3. Decode JSON to PHP array
$response = json_decode($data, true);

// 4. Extract info (check if valid)
if (isset($response['Body']['stkCallback'])) {
    $callback = $response['Body']['stkCallback'];
    $ResultCode = $callback['ResultCode'];

    if ($ResultCode == 0) {
        // 5. Extract useful data
        $CheckoutRequestID   = $callback['CheckoutRequestID'];
        $Amount              = $callback['CallbackMetadata']['Item'][0]['Value'];
        $MpesaReceiptNumber  = $callback['CallbackMetadata']['Item'][1]['Value'];
        $PhoneNumber         = $callback['CallbackMetadata']['Item'][4]['Value'];

        // 6. Connect to DB
        $conn = new mysqli("localhost", "root", "", "geogon_store");

        // 7. Save payment to 'payments' table
        $stmt = $conn->prepare("INSERT INTO payments (mpesa_receipt, phone, amount, checkout_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $MpesaReceiptNumber, $PhoneNumber, $Amount, $CheckoutRequestID);
        $stmt->execute();
        $stmt->close();

        // 8. Mark order as paid
        $update = $conn->prepare("UPDATE orders SET paid = 1 WHERE checkout_id = ?");
        $update->bind_param("s", $CheckoutRequestID);
        $update->execute();
        $update->close();

        // 9. Send SMS confirmation to user
        include 'sms.php';
        sendSMS($PhoneNumber, "Payment received. Thank you for shopping at Geogon Store!");

        // 10. Close DB connection
        $conn->close();
    }
}

// 11. Respond to Safaricom with success
echo json_encode(["ResultCode" => 0, "ResultDesc" => "Confirmation Received Successfully"]);
?>