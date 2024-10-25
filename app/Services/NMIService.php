<?php

namespace App\Services;

use GuzzleHttp\Client;

class NMIService
{
    protected $client;
    protected $endpoint;
    protected $username;
    protected $password;

    public function __construct()
    {
        $this->client = new Client();
        $this->endpoint = config('services.nmi.endpoint');
        $this->username = config('services.nmi.username');
        $this->password = config('services.nmi.password');
    }

    public function processPayment($data)
    {
        try {
            $response = $this->client->post($this->endpoint, [
                'form_params' => array_merge($data, [
                    'username' => $this->username,
                    'password' => $this->password,
                    'type' => 'sale'
                ]),
            ]);

            $responseBody = $response->getBody()->getContents();
            parse_str($responseBody, $parsedResponse); // Parse the URL-encoded response

            return $parsedResponse;
        } catch (RequestException $e) {
            // Return the error response
            if ($e->hasResponse()) {
                return json_decode($e->getResponse()->getBody()->getContents(), true);
            } else {
                return ['error' => 'Unable to process payment. No response from NMI.'];
            }
        }
    }
}
