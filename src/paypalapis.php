<?php
use Slim\Http\Request;
use Slim\Http\Response;

$app->post('/api/orders/create', function (Request $request, Response $response) {
    $paypalService = new PayPalOrderServices();
    $body = $request->getParsedBody();
    $amount = $body['amount'];
    $currencyCodeType = $body['currencyCodeType'];
    $returnURL = $body['returnURL'];
    $cancelURL = $body['cancelURL'];
    return $paypalService->createOrder($response, $amount, $currencyCodeType, $returnURL, $cancelURL);
});

$app->post('/api/orders/{orderID}/capture', function (Request $request, Response $response, $args) {
    $paypalService = new PayPalOrderServices();
    $orderID = $args['orderID'];
    return $paypalService->captureOrder($response, $orderID);
});

$app->post('/api/orders/{orderID}/authorize', function (Request $request, Response $response, $args) {
    $paypalService = new PayPalOrderServices();
    $orderID = $args['orderID'];
    $body = $request->getParsedBody();
    $amount = $body['amount'];
    $currencyCodeType = $body['currencyCodeType'];
    return $paypalService->authorizeOrder($response, $orderID, $amount, $currencyCodeType);
});

$app->post('/api/payments/{authorizationId}/capture', function (Request $request, Response $response, $args) {
    $paypalService = new PayPalPaymentServices();
    $authorizationId = $args['authorizationId'];
    $body = $request->getParsedBody();
    $amount = $body['amount'];
    $currencyCodeType = $body['currencyCodeType'];
    return $paypalService->capturePayment($response, $authorizationId, $amount, $currencyCodeType);
});
?>
