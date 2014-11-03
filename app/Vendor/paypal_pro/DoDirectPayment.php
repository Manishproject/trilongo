<?php
// Include required library files.
require_once('includes/config.php');
require_once('includes/paypal.class.php');

// Create PayPal object.
$sandbox=TRUE;
$api_username="vineet.kumar-facilitator_api1.deemtech.com";
$api_password="1373970737";
$api_signature="A9vWK8UcxEbbjvJN.jaiKMxqZ47KA5gq3pVfneR0iRk0AtZrAr1n8jMJ";

$PayPalConfig = array(
    'Sandbox' => $sandbox,
    'APIUsername' => $api_username,
    'APIPassword' => $api_password,
    'APISignature' => $api_signature
);

$PayPal = new PayPal($PayPalConfig);

// Prepare request arrays
$DPFields = array(
    'paymentaction' => '', // How you want to obtain payment.  Authorization indidicates the payment is a basic auth subject to settlement with Auth & Capture.  Sale indicates that this is a final sale for which you are requesting payment.  Default is Sale.
    'ipaddress' => '', // Required.  IP address of the payer's browser.
    'returnfmfdetails' => '' // Flag to determine whether you want the results returned by FMF.  1 or 0.  Default is 0.
);

$CCDetails = array(
    'creditcardtype' => 'Visa', // Required. Type of credit card.  Visa, MasterCard, Discover, Amex, Maestro, Solo.  If Maestro or Solo, the currency code must be GBP.  In addition, either start date or issue number must be specified.
    'acct' => '4072265591376870', // Required.  Credit card number.  No spaces or punctuation.
    'expdate' => '012014', // Required.  Credit card expiration date.  Format is MMYYYY
    'cvv2' => '962', // Requirements determined by your PayPal account settings.  Security digits for credit card.
    'startdate' => '', // Month and year that Maestro or Solo card was issued.  MMYYYY
    'issuenumber' => '' // Issue number of Maestro or Solo card.  Two numeric digits max.
);

$PayerInfo = array(
    'email' => '', // Email address of payer.
    'firstname' => 'John', // Required.  Payer's first name.
    'lastname' => 'Doe' // Required.  Payer's last name.
);

$PaymentDetails = array(
    'amt' => '10.00', // Required.  Total amount of order, including shipping, handling, and tax.
    'currencycode' => 'USD', // Required.  Three-letter currency code.  Default is USD.
);
$PayPalRequestData = array(
    'CCDetails' => $CCDetails,
    'PayerInfo' => $PayerInfo,
    'PaymentDetails' => $PaymentDetails,
);
$PayPalResult = $PayPal->DoDirectPayment($PayPalRequestData);

// Write the contents of the response array to the screen for demo purposes.
echo '<pre />';
print_r($PayPalResult);
?>