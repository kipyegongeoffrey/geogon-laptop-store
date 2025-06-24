<?php 
include 'config.php';

// Step 1: Get form input
$name = $_POST['name'];
$phone = $_POST['phone'];
$location = $_POST['location'];
$amount = 1; // You can later make this dynamic from cart

// Step 2: Get access token
$credentials = base64_encode($consumerKey . ':' . $consumerSecret);
$url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Basic ' . $credentials]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
curl_close($ch);

$token = json_decode($result)->access_token;

// Step 3: Initiate STK Push
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

// Step 4: Save order to database
$conn = new mysqli("localhost", "root", "", "geogon_store"); // replace with your DB if different

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("INSERT INTO orders (customer_name, phone, location, amount) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sssd", $name, $phone, $location, $amount);
$stmt->execute();
$stmt->close();
$conn->close();

// Show message to customer
echo "<h3>M-PESA payment initiated.</h3>";
echo "<p>Please complete payment on your phone ($phone).</p>";
?>
