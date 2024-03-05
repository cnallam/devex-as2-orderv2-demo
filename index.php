<?php
error_reporting(E_ALL ^ E_DEPRECATED);
require 'vendor/autoload.php';

$app = new \Slim\App();

// Define your Slim application routes here
// ...
require 'src/paypalapis.php';
require 'src/paypalauthenticationservices.php'; 
require 'src/paypalorderservices.php'; 
require 'src/paypalpaymentservices.php'; 

// Redirect to checkout.html
$app->get('/', function ($request, $response, $args) {
    return $response->withRedirect('/public/checkout.php');
});

$app->run();
?>