<?php
class PayPalPaymentServices
{
    private $paypalAuthenticationService;
    private $baseUrl;

    public function __construct()
    {
        $this->paypalAuthenticationService = new PayPalAuthenticationServices();
        $this->baseUrl = getenv('BASE_URL');
    }

    public function capturePayment($response, $authorizationId, $amount, $currencyCodeType)
    {
        $accessToken = $this->paypalAuthenticationService->getAccessToken();

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "{$this->baseUrl}/v2/payments/authorizations/$authorizationId/capture");
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer $accessToken"
        ]);

        $paymentData = [
            'amount' => [
                'currency_code' => $currencyCodeType,
                'value' => $amount
            ],
            'final_capture' => true
        ];
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($paymentData));
        $result = curl_exec($curl);
        $info = curl_getinfo($curl);
        if ($info['http_code'] != 201) {
            // Handle error
        }

        curl_close($curl);
        return $response->withJson(json_decode($result), 201);
    }

    public function reAuthorizePayment($response, $authorizationId, $amount, $currencyCodeType)
    {
        $accessToken = $this->paypalAuthenticationService->getAccessToken();

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "{$this->baseUrl}/v2/payments/authorizations/$authorizationId/reauthorize");
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer $accessToken"
        ]);

        $paymentData = [
            'amount' => [
                'currency_code' => $currencyCodeType,
                'value' => $amount
            ]
        ];
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($paymentData));
        $result = curl_exec($curl);
        $info = curl_getinfo($curl);
        if ($info['http_code'] != 201) {
            // Handle error
        }

        curl_close($curl);
        return $response->withJson(json_decode($result), 201);
    }
}
?>