<?php
require 'vendor/autoload.php'; // Only if using Composer

use AfricasTalking\SDK\AfricasTalking;

// Credentials
$username = "sandbox"; // Use "sandbox" for testing
$apiKey   = "42Z30QY5fPjwW6qxKQk181UavW0L9oApA8FeIsom8p5HTss4"; // Replace with your key

$AT = new AfricasTalking($username, $apiKey);
$sms = $AT->sms();

function sendSMS($to, $message) {
    global $sms;

    try {
        $result = $sms->send([
            'to' => $to,
            'message' => $message
        ]);
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>