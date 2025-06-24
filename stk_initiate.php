<?php
include 'config.php';

// Step 1: Get Access Token
$credentials = base64_encode($consumerKey . ':' . $consumerSecret);
$url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Basic ' . $credentials]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
curl_close($ch);

$token = json_decode($result)->access_token;

// Step 2: Set up STK Push parameters
$phone = "254708374149"; // Use this TEST number ONLY in sandbox
$amount = 1; // Test amount

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

// Display response
echo "<pre>";
print_r(json_decode($response));
echo "</pre>";
?>
