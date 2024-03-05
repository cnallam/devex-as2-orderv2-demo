paypal.Buttons({
    createOrder: function(data, actions) {
        // Set up the transaction
        return fetch('/api/orders/create', {
            method: 'post',
            headers: {
                'content-type': 'application/json'
            },
            body: JSON.stringify({
                amount: '10.00', // Replace with the actual amount
                currencyCodeType: 'USD', // Replace with the actual currency code
                returnURL: 'http://example.com/success', // Replace with the actual return URL
                cancelURL: 'http://example.com/cancel' // Replace with the actual cancel URL
            })
        }).then(function(response) {
            return response.json();
        }).then(function(orderData) {
            return orderData.id; // Use the key from the response JSON that contains the order ID
        });
    },
    onApprove: function(data, actions) {
        // This function captures the funds from the transaction
        return fetch('/api/orders/' + data.orderID + '/authorize', {
            method: 'post'
        }).then(function(response) {
            return response.json();
        }).then(function(captureData) {
            // This function shows a transaction success message to your buyer
            alert('Transaction completed by ' + captureData.payer.name.given_name);
        });
    },
    onCancel: function(data) {
        // Show a cancel page, or return to cart
        window.location.href = 'http://example.com/cancel';
    },
    onError: function(err) {
        // Show an error page here, when an error occurs
        console.error('Checkout error', err);
    }
}).render('#paypal-button-container');