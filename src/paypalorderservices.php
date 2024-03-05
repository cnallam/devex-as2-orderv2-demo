<?php
class PayPalOrderServices
{
    private $paypalAuthenticationService;
    private $baseUrl;

    public function __construct()
    {
        $this->paypalAuthenticationService = new PayPalAuthenticationServices();
        $this->baseUrl = getenv('BASE_URL');
    }

    public function createOrder($response, $amount, $currencyCodeType, $returnURL, $cancelURL)
    {
        $accessToken = $this->paypalAuthenticationService->getAccessToken();
        $orderData = [
            'intent' => 'AUTHORIZE',
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => $currencyCodeType,
                        'value' => $amount
                    ]
                ]
            ],
            'application_context' => [
                'return_url' => $returnURL,
                'cancel_url' => $cancelURL
            ]
        ];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "{$this->baseUrl}/v2/checkout/orders");
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer $accessToken"
        ]);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($orderData));

        $result = curl_exec($curl);
        $info = curl_getinfo($curl);
        if ($info['http_code'] != 201) {
            // Handle error
        }

        curl_close($curl);
        return $response->withJson(json_decode($result), 201);
    }

    public function captureOrder($response, $orderID)
    {
        $accessToken = $this->paypalAuthenticationService->getAccessToken();

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "{$this->baseUrl}/v2/checkout/orders/$orderID/capture");
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer $accessToken"
        ]);

        $result = curl_exec($curl);
        $info = curl_getinfo($curl);
        if ($info['http_code'] != 201) {
            // Handle error
        }

        curl_close($curl);
        return $response->withJson(json_decode($result), 201);
    }

    public function authorizeOrder($response, $orderID)
    {
        $accessToken = $this->paypalAuthenticationService->getAccessToken();

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "{$this->baseUrl}/v2/checkout/orders/$orderID/authorize");
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer $accessToken"
        ]);

        $result = curl_exec($curl);
        $info = curl_getinfo($curl);
        if ($info['http_code'] != 201) {
            // Handle error
        }

        curl_close($curl);
        return $response->withJson(json_decode($result), 201);
    }

    public function getOrderDetails($response, $orderID)
    {
        $accessToken = $this->paypalAuthenticationService->getAccessToken();

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "{$this->baseUrl}/v2/checkout/orders/$orderID");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $accessToken"
        ]);

        $result = curl_exec($curl);
        $info = curl_getinfo($curl);
        if ($info['http_code'] != 200) {
            // Handle error
        }

        curl_close($curl);
        return $response->withJson(json_decode($result), 200);
    }
}
?>