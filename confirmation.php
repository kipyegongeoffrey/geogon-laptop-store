<?php
// Read JSON raw input
$data = file_get_contents('php://input');
$response = json_decode($data);

// Open log file for debugging
file_put_contents('callback_log.txt', $data, FILE_APPEND);

// If the response is valid, extract fields
if (!empty($response->Body->stkCallback)) {
    $callback = $response->Body->stkCallback;

    $mpesaReceipt = $callback->CallbackMetadata->Item[1]->Value ?? '';
    $amount       = $callback->CallbackMetadata->Item[0]->Value ?? 0;
    $phone        = $callback->CallbackMetadata->Item[4]->Value ?? '';
    $transactionDate = $callback->CallbackMetadata->Item[3]->Value ?? '';
    $orderRef     = $callback->CheckoutRequestID ?? '';
    $status       = $callback->ResultCode == 0 ? "Success" : "Failed";

    // Save to DB
    $conn = new mysqli("localhost", "root", "", "geogon_store");

    if (!$conn->connect_error) {
        $stmt = $conn->prepare("INSERT INTO payments (mpesa_receipt_number, transaction_date, phone_number, amount, order_ref, status, raw_response) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $mpesaReceipt, $transactionDate, $phone, $amount, $orderRef, $status, $data);
        $stmt->execute();
        $stmt->close();
        $conn->close();
    }
}

// Send success response to Safaricom
echo json_encode(["ResultCode" => 0, "ResultDesc" => "Confirmation Received"]);
?>