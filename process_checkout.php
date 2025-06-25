<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'config.php'; // This must define all required keys below

// Check required POST fields
if (!isset($_POST['name'], $_POST['phone'], $_POST['location'], $_POST['cart_data'])) {
    die("Missing required POST data.");
}

$name = $_POST['name'];
$phone = $_POST['phone'];
$location = $_POST['location'];
$email = $_POST['email'] ?? '';
$cart = json_decode($_POST['cart_data'], true);

if (!$cart || !is_array($cart)) {
    die("Invalid or missing cart data.");
}

// Calculate total amount
$amount = 0;
foreach ($cart as $item) {
    $amount += $item['price'] * $item['quantity'];
}

// === Safaricom Access Token ===
$credentials = base64_encode($consumerKey . ':' . $consumerSecret);
$url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Basic ' . $credentials]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
curl_close($ch);

$tokenData = json_decode($result, true);
if (!isset($tokenData['access_token'])) {
    die("Failed to get access token: " . $result);
}
$token = $tokenData['access_token'];

// === STK Push ===
$timestamp = date("YmdHis");
$password = base64_encode($BusinessShortCode . $Passkey . $timestamp);
$stkUrl = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

$stkData = [
    "BusinessShortCode" => $BusinessShortCode,
    "Password" => $password,
    "Timestamp" => $timestamp,
    "TransactionType" => "CustomerPayBillOnline",
    "Amount" => $amount,
    "PartyA" => $phone,
    "PartyB" => $BusinessShortCode,
    "PhoneNumber" => $phone,
    "CallBackURL" => $callbackUrl,
    "AccountReference" => $AccountReference,
    "TransactionDesc" => $TransactionDesc
];

$headers = [
    "Content-Type: application/json",
    "Authorization: Bearer $token"
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $stkUrl);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($stkData));
$response = curl_exec($ch);
curl_close($ch);

$responseData = json_decode($response, true);
if (!isset($responseData['CheckoutRequestID'])) {
    die("STK Push failed: " . $response);
}
$checkoutID = $responseData['CheckoutRequestID'];

// === Save to DB ===
$conn = new mysqli("localhost", "root", "", "geogon_store");
if ($conn->connect_error) {
    die("DB connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("INSERT INTO orders (name, phone, location, amount, checkout_id) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssis", $name, $phone, $location, $amount, $checkoutID);
$stmt->execute();
$order_id = $stmt->insert_id;

// === Save Items ===
foreach ($cart as $item) {
    $product_id = $item['id'];
    $product_name = $item['name'];
    $product_price = $item['price'];
    $quantity = $item['quantity'];

    $itemStmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, name, price, quantity) VALUES (?, ?, ?, ?, ?)");
    $itemStmt->bind_param("iisdi", $order_id, $product_id, $product_name, $product_price, $quantity);
    $itemStmt->execute();
    $itemStmt->close();
}

// === Send SMS ===
include 'sms.php';
sendSMS($phone, "Thank you for ordering at Geogon Store. Await payment confirmation.");

// === Send Email ===
if (!empty($email)) {
    $message = "Hello $name,\nThank you for shopping at Geogon Store.\n\nOrder:\n";
    foreach ($cart as $item) {
        $message .= "- {$item['name']} x{$item['quantity']} @ KES {$item['price']}\n";
    }
    $message .= "\nTotal: KES $amount";
    mail($email, "Your Geogon Store Order", $message, "From: geogon@example.com");
}

// === Finish ===
$_SESSION['last_order_id'] = $order_id;
$stmt->close();
$conn->close();
header("Location: receipt.php");
exit();
