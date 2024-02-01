paypal.Buttons({
    // Set up the transaction
    createOrder: function(data, actions) {
        return fetch('/api/orders', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            // Add necessary request body if required by your backend
        }).then(function(res) {
            return res.json();
        }).then(function(orderData) {
            // Extract the id from the response if it follows PayPal's order format
            return orderData.id;
        });
    },

    // Finalize the transaction
    onApprove: function(data, actions) {
        return fetch(`/api/orders/${data.orderID}/capture`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            // Add necessary request body if required by your backend
        }).then(function(res) {
            return res.json();
        }).then(function(orderData) {
            // Handle successful transaction, e.g., display a success message or redirect
            console.log('Transaction completed!', orderData);
            // Implement your success logic here
        });
    },

    // Handle errors
    onError: function(err) {
        // Handle errors, e.g., display an error message
        console.error('Checkout error', err);
    }
}).render('#paypal-button-container');
