<?php
namespace App\Models;
use stdClass;

/**
 *
 */
class Empresa
{
    /**
     * @var string
     */
    public string $Nombre;
    /**
     * @var string
     */
    public string $CIF;
    /**
     * @var string
     */
    public string $RazonSocial;
    /**
     * @var bool
     */
    public bool $Verifactu;

    public string $certificate;
    /**
     * @param string $Nombre
     * @param string $CIF
     * @param string $RazonSocial
     * @param bool $Verifactu
     * @param string $certificate
     */
    public function __construct(string $Nombre, string $CIF, string $RazonSocial, string $certificate, bool $Verifactu = true)
    {
        $this->Nombre = $Nombre;
        $this->CIF = $CIF;
        $this->RazonSocial = $RazonSocial;
        $this->Verifactu = $Verifactu;
        $this->certificate = $certificate;
    }

    /**
     * @return string
     */
    public function getNombre(): string
    {
        return $this->Nombre;
    }

    /**
     * @param string $Nombre
     * @return void
     */
    public function setNombre(string $Nombre): void
    {
        $this->Nombre = $Nombre;
    }

    /**
     * @return string
     */
    public function getCIF(): string
    {
        return $this->CIF;
    }

    /**
     * @param string $CIF
     * @return void
     */
    public function setCIF(string $CIF): void
    {
        $this->CIF = $CIF;
    }

    /**
     * @return string
     */
    public function getRazonSocial(): string
    {
        return $this->RazonSocial;
    }

    /**
     * @param string $RazonSocial
     * @return void
     */
    public function setRazonSocial(string $RazonSocial): void
    {
        $this->RazonSocial = $RazonSocial;
    }

    /**
     * @return bool
     */
    public function getVerifactu(): bool
    {
        return $this->Verifactu;
    }

    /**
     * @param bool $Verifactu
     * @return void
     */
    public function setVerifactu(bool $Verifactu): void
    {
        $this->Verifactu = $Verifactu;
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
     */
    public function setCertificate(string $certificate): void
    {
        $this->certificate = $certificate;
    }

    public function toConsulta(): \stdClass
    {
        $data = new stdClass();
        $data->CIF = $this->CIF;
        return $data;
    }
    public function toCreate(): \stdClass{
        $data = new stdClass();
        $data->Nombre = $this->Nombre;
        $data->CIF = $this->CIF;
        $data->RazonSocial = $this->RazonSocial;
        $data->Verifactu = $this->Verifactu;
        return $data;
    }
}