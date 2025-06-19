<?php
namespace App\Models;
/**
 *
 */
class Client
{
    /**
     * @var string
     */
    public string $NIF;
    /**
     * @var string
     */
    public string $RazonSocial;

    /**
     * @param string $NIF
     * @param string $RazonSocial
     */
    public function __construct(string $NIF, string $RazonSocial)
    {
        $this->NIF = $NIF;
        $this->RazonSocial = $RazonSocial;
    }

    /**
     * @return string
     */
    public function getNIF(): string
    {
        return $this->NIF;
    }

    /**
     * @param string $NIF
     */
    public function setNIF(string $NIF): void
    {
        $this->NIF = $NIF;
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
     */
    public function setRazonSocial(string $RazonSocial): void
    {
        $this->RazonSocial = $RazonSocial;
    }

}