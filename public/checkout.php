<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PayPal Checkout</title>
    <script src="https://www.paypal.com/sdk/js?client-id=<?php echo getenv('PAYPAL_CLIENT_ID'); ?>&currency=USD&intent=authorize"></script>
</head>
<body>
    <!-- Add the PayPal button container -->
    <div id="paypal-button-container"></div>

    <!-- Include the JavaScript file -->
    <script src="app.js"></script>
</body>
</html>