<?php
class PayPalAuthenticationServices
{
    private $clientId;
    private $secret;
    private $baseUrl;

    public function __construct()
    {
        $this->clientId = getenv('PAYPAL_CLIENT_ID');
        $this->secret = getenv('PAYPAL_CLIENT_SECRET');
        $this->baseUrl = getenv('BASE_URL');
    }

    public function getAccessToken()
    {
        return $this->authenticate();
    }

    private function authenticate()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "{$this->baseUrl}/v1/oauth2/token");
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($curl, CURLOPT_USERPWD, $this->clientId . ":" . $this->secret);
        curl_setopt($curl, CURLOPT_POSTFIELDS, "grant_type=client_credentials");

        $response = curl_exec($curl);
        $info = curl_getinfo($curl);
        if ($info['http_code'] != 200) {
            // Handle error
            throw new Exception('Failed to retrieve access token from PayPal: ' . curl_error($curl), $info['http_code']);
        }

        curl_close($curl);
        $jsonResponse = json_decode($response, true);
        
        if (!isset($jsonResponse['access_token'])) {
            // Handle error
            throw new Exception('Access token not found in the response.');
        }

        return $jsonResponse['access_token'];
    }
}
?>