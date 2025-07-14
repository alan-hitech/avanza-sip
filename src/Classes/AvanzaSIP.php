<?php
namespace AvanzaSip\Classes;

use AvanzaSip\Models\Factura;
use AvanzaSip\Models\Empresa;
use AvanzaSip\Enums\TipoRectificativa;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 *
 */
class AvanzaSIP
{
    /**
     * @var string
     */
    protected string $urlTest = "https://test-avanzasif.avanzapi.es";
    /**
     * @var string
     */
    protected string $url = "https://avanzasif.avanzapi.es";
    /**
     * @var string
     */
    public string $certificate;
    /**
     * @var string
     */
    public string $password;
    /**
     * @var string
     */
    public string $authToken;
    /**
     * @var bool|mixed
     */
    public bool $test = false;
    /**
     * @var bool|mixed
     */
    public bool $debug = false;

    /**
     * @param $certificate
     * @param string $password
     * @param $authToken
     * @param $test
     * @param $debug
     */
    public function __construct($certificate, string $password, $authToken, $test = false, $debug = false)
    {
        $this->certificate = $certificate;
        $this->password = $password;
        $this->authToken = $authToken;
        $this->test = $test;
        $this->debug = $debug;
    }

    /**
     * @return string
     */
    public function getCertificate(): string
    {
        return $this->certificate;
    }

    /**
     * @param string $certificate
     * @return void
     */
    public function setCertificate(string $certificate): void
    {
        $this->certificate = $certificate;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return void
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getAuthToken(): string
    {
        return $this->authToken;
    }

    /**
     * @param string $authToken
     * @return void
     */
    public function setAuthToken(string $authToken): void
    {
        $this->authToken = $authToken;
    }

    /**
     * @return bool
     */
    public function isTest(): bool
    {
        return $this->test;
    }

    /**
     * @param bool $test
     * @return void
     */
    public function setTest(bool $test): void
    {
        $this->test = $test;
    }

    /**
     * @return bool
     */
    public function isDebug(): bool
    {
        return $this->debug;
    }

    /**
     * @param bool $debug
     * @return void
     */
    public function setDebug(bool $debug): void
    {
        $this->debug = $debug;
    }

    /**
     * @param string $endpoint
     * @param \stdClass $data
     * @param string $method
     * @return mixed
     * @throws \Exception
     */
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
                    'contents' => fopen($this->certificate, 'rb'),
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

    /**
     * @param Factura $factura
     * @return mixed
     * @throws \Exception
     */
    public function altaFactura(Factura $factura)
    {
        return $this->goCurl('altaFactura', $factura->toAlta());
    }

    /**
     * @param Factura $factura
     * @return bool
     * @throws \Exception
     */
    public function consultaFactura(Factura $factura):bool
    {
        return $this->goCurl('consultInvoice', $factura->toConsulta());
    }

    /**
     * @param Factura $factura
     * @return mixed
     * @throws \Exception
     */
    public function editFactura(Factura $factura)
    {
        return $this->goCurl('altaFactura', $factura->toEdit());
    }

    /**
     * @param Factura $factura
     * @param Factura $rectificada
     * @return mixed
     * @throws \Exception
     */
    public function rectificativa(Factura $factura, Factura $rectificada)
    {
        return $this->goCurl('altaFactura', $factura->toRectificativa($rectificada));
    }

    /**
     * @param Empresa $empresa
     * @return mixed
     * @throws \Exception
     */
    public function consultCompany(Empresa $empresa)
    {
        return $this->goCurl('consultCompany', $empresa->toConsulta())->Exist;
    }

    /**
     * @param Empresa $empresa
     * @return mixed
     * @throws \Exception
     */
    public function createCompany(Empresa $empresa)
    {
        return $this->goCurl('createCompany',$empresa->toCreate());
    }

    /**
     * @param Factura $factura
     * @return mixed
     * @throws \Exception
     */
    public function getQR(Factura $factura)
    {
        return $this->goCurl('getQR', $factura->toQR());
    }
}
