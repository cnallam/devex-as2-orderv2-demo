<?php
error_reporting(E_ALL ^ E_DEPRECATED);
// Assuming $app is a Slim application or similar that provides a routing mechanism
require 'vendor/autoload.php';

#$app = new \Slim\App();

$app = new \Slim\App(array(
    'debug' => true
));

// Function to generate OAuth 2.0 Access Token
function getAccessToken() {
    $clientId = getenv('PAYPAL_CLIENT_ID');
    $secret = getenv('PAYPAL_CLIENT_SECRET');
    $url = getenv('BASE_URL') . "/v1/oauth2/token";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, $clientId . ":" . $secret);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");

    $response = curl_exec($ch);

    if (empty($response)) {
        die("Error: No response.");
    } else {
        $json = json_decode($response);
        curl_close($ch);
        return $json->access_token;
    }
}

// POST /api/orders - Create an order
$app->any('/api/orders', function () use ($app) {
    $accessToken = getAccessToken();
    
    $url = getenv('BASE_URL') . "/v2/checkout/orders";

    $orderData = array(
        // Define your order structure here as per PayPal's documentation.
        // Example structure:
        'intent' => 'CAPTURE',
        'purchase_units' => array(
            array(
                'amount' => array(
                    'currency_code' => 'USD',
                    'value' => '100.00'
                )
            )
        )
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json",
        "Authorization: Bearer " . $accessToken
    ));
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($orderData));

    $response = curl_exec($ch);
    
    curl_close($ch);
    
    echo $response;
});

// POST /api/orders/{orderID}/capture - Capture payment for an order
$app->post('/api/orders/{orderID}/capture', function ($request, $response, $args) {
    $accessToken = getAccessToken();
    $orderID = $args['orderID'];
    $url = getenv('BASE_URL') . "/v2/checkout/orders/$orderID/capture";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json",
        "Authorization: Bearer " . $accessToken
    ));
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    echo $response;
});

$app->run();

?>
