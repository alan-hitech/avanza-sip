<?php
namespace App\Classes;

use App\Models\AvanzaSIPFactura;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class AvanzaSIP
{
    protected string $urlTest = "https://test-avanzasif.avanzapi.es";
    protected string $url = "https://avanzasif.avanzapi.es";
    public string $certificate;
    public string $password;
    public string $authToken;
    public bool $test = false;
    public bool $debug = false;
    public function __construct($certificate, string $password, $authToken, $test = false, $debug = false)
    {
        $this->certificate = $certificate;
        $this->password = $password;
        $this->authToken = $authToken;
        $this->test = $test;
        $this->debug = $debug;
    }

    public function getCertificate(): string
    {
        return $this->certificate;
    }

    public function setCertificate(string $certificate): void
    {
        $this->certificate = $certificate;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getAuthToken(): string
    {
        return $this->authToken;
    }

    public function setAuthToken(string $authToken): void
    {
        $this->authToken = $authToken;
    }

    public function isTest(): bool
    {
        return $this->test;
    }

    public function setTest(bool $test): void
    {
        $this->test = $test;
    }

    public function isDebug(): bool
    {
        return $this->debug;
    }

    public function setDebug(bool $debug): void
    {
        $this->debug = $debug;
    }

    private function goCurl(string $endpoint, \stdClass $data, string $method = "POST")
    {
        $url = ($this->test) ? $this->urlTest : $this->url;
        $client = new Client([
            'base_uri' => $url,
            'verify' =>  !$this->test,
        ]);
        $data->Password = $this->password;
        if($this->debug) {
            var_dump(json_encode($data));
        }
        $options = [
            'headers' => [
                'Authorization' => "Token {$this->authToken}"
            ],
            'multipart' => [
                [
                    'name' => 'certificado',
                    'contents' => fopen($this->certificate, 'r'),
                    'filename' => basename($this->certificate)
                ],
                [
                    'name' => 'data',
                    'contents' => json_encode($data)
                ]
            ]
        ];

        try {
            $response = $client->request($method, "{$endpoint}/", $options);
            $responseBody = $response->getBody()->getContents();
            if($this->debug) {
                echo "\nResponse: {$responseBody}\nError: \n";
            }
            $result = json_decode($responseBody);
            if (isset($result->error_code)) {
                throw new \Exception($result->error_message, $result->error_code);
            }
            return $result;
        } catch (GuzzleException $e) {
            echo "\nError: {$e->getMessage()}\n";
            throw new \Exception("Request failed: " . $e->getMessage(), $e->getCode());
        }
    }
    public function altaFactura(AvanzaSIPFactura $factura)
    {
        return $this->goCurl('altaFactura', $factura->toAlta("123456"));
    }
    public function editFactura(AvanzaSIPFactura $factura, string $idFacturaAntigua)
    {
        return $this->goCurl('altaFactura', $factura->toEdit("123456" , $idFacturaAntigua));
    }
    public function consultCompany($data)
    {
        $result = $this->goCurl('consultCompany', $data);
        return $result->Exist;
    }
    public function createCompany($data)
    {
        return $this->goCurl('createCompany',$data);
    }
    public function getQR($invoiceID)
    {
        $data = new \stdClass();
        $data->id = $invoiceID;
        return $this->goCurl('getQR', $data);
    }
}
