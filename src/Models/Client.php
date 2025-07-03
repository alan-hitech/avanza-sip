<?php

namespace AvanzaSip\Models;

use AvanzaSip\Enums\TipoNIF;

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
    public bool $nacional;
    public TipoNIF $tipoNIF;

    /**
     * @param string $NIF
     * @param string $RazonSocial
     */
    public function __construct(string $NIF, string $RazonSocial, bool $nacional = true, TipoNIF $tipoNIF = TipoNIF::NIF)
    {
        $this->NIF = $NIF;
        $this->RazonSocial = $RazonSocial;
        $this->tipoNIF = $tipoNIF;
        $this->nacional = $nacional;
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

    public function isNacional(): bool
    {
        return $this->nacional;
    }

    public function setNacional(bool $nacional): void
    {
        $this->nacional = $nacional;
    }

    public function getTipoNIF(): TipoNIF
    {
        return $this->tipoNIF;
    }

    public function setTipoNIF(TipoNIF $tipoNIF): void
    {
        $this->tipoNIF = $tipoNIF;
    }

}