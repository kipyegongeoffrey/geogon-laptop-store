<?php
// Switch to 'live' when ready
$environment = 'sandbox'; // or 'live'

// Common settings
$BusinessShortCode = '4490750'; // Replace with your Paybill/Till number
$Passkey = 'YOUR_PASSKEY'; // Replace with the Daraja passkey
$AccountReference = 'GeogonOrder'; // e.g. customer name or order id
$TransactionDesc = 'Payment for Geogon Store Order';
$callbackUrl = ' https://150a-102-219-208-161.ngrok-free.app -> http://localhost:80 '; // Use HTTPS in production

// Environment-specific settings
if ($environment === 'sandbox') {
    $consumerKey = '42Z30QY5fPjwW6qxKQk181UavW0L9oApA8FeIsom8p5HTss4';
    $consumerSecret = 'nhJaMVcCjUdKfaZPUmAeARCdiKbAqUTHaAFSws3KWQZAAKDjNgMXJxgGm01gRny1';
    $accessTokenUrl = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
    $stkPushUrl = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
} else {
    $consumerKey = 'YOUR_LIVE_CONSUMER_KEY';
    $consumerSecret = 'YOUR_LIVE_CONSUMER_SECRET';
    $accessTokenUrl = 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
    $stkPushUrl = 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
}
