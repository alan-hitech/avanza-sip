<?php
namespace AvanzaSip\Models;
use AvanzaSip\Enums\CalificacionOperacion;
use AvanzaSip\Enums\OperacionExcenta;
use AvanzaSip\Enums\Regimen;
use AvanzaSip\Enums\TipoImpuesto;

class FacturaImpuesto
{
    public TipoImpuesto $tipoImpuesto;
    public Regimen $regimen;
    public CalificacionOperacion $calificacionOperacion;
    public float $impuesto;
    public float $baseImponible;
    public float $cuota;
    public ?OperacionExcenta $excenta = null;
    /**
     * @param TipoImpuesto $tipoImpuesto
     * @param Regimen $regimen
     * @param CalificacionOperacion $calificacionOperacion
     * @param float $impuesto
     * @param float $baseImponible
     * @param float $cuota
     */
    public function __construct(TipoImpuesto $tipoImpuesto, Regimen $regimen, CalificacionOperacion $calificacionOperacion, float $impuesto, float $baseImponible, float $cuota, $excenta = null)
    {
        $this->tipoImpuesto = $tipoImpuesto;
        $this->regimen = $regimen;
        $this->calificacionOperacion = $calificacionOperacion;
        $this->impuesto = $impuesto;
        $this->baseImponible = $baseImponible;
        $this->cuota = $cuota;
        $this->excenta = $excenta;
    }

    /**
     * @return TipoImpuesto
     */
    public function getTipoImpuesto(): TipoImpuesto
    {
        return $this->tipoImpuesto;
    }

    /**
     * @param TipoImpuesto $tipoImpuesto
     */
    public function setTipoImpuesto(TipoImpuesto $tipoImpuesto): void
    {
        $this->tipoImpuesto = $tipoImpuesto;
    }

    /**
     * @return string
     */
    public function getRegimen(): Regimen
    {
        return $this->regimen;
    }

    /**
     * @param string $regimen
     */
    public function setRegimen(Regimen $regimen): void
    {
        $this->regimen = $regimen;
    }

    /**
     * @return CalificacionOperacion
     */
    public function getCalificacionOperacion(): CalificacionOperacion
    {
        return $this->calificacionOperacion;
    }

    /**
     * @param CalificacionOperacion $calificacionOperacion
     */
    public function setCalificacionOperacion(CalificacionOperacion $calificacionOperacion): void
    {
        $this->calificacionOperacion = $calificacionOperacion;
    }

    /**
     * @return float
     */
    public function getImpuesto(): float
    {
        return $this->impuesto;
    }

    /**
     * @param float $impuesto
     */
    public function setImpuesto(float $impuesto): void
    {
        $this->impuesto = $impuesto;
    }

    /**
     * @return float
     */
    public function getBaseImponible(): float
    {
        return $this->baseImponible;
    }

    /**
     * @param float $baseImponible
     */
    public function setBaseImponible(float $baseImponible): void
    {
        $this->baseImponible = $baseImponible;
    }

    /**
     * @return float
     */
    public function getCuota(): float
    {
        return $this->cuota;
    }

    /**
     * @param float $cuota
     */
    public function setCuota(float $cuota): void
    {
        $this->cuota = $cuota;
    }
    public function aFactura()
    {
        $afactura = new \stdClass();
        $afactura->Impuesto = $this->getTipoImpuesto();
        if(in_array($this->getTipoImpuesto(), [TipoImpuesto::IVA,TipoImpuesto::IPSI], true)) {
            $afactura->ClaveRegimen = $this->getRegimen();
        }
        $afactura->CalificacionOperacion = $this->getCalificacionOperacion();
        if(in_array($this->getCalificacionOperacion(), [CalificacionOperacion::OPERACION_NO_SUJETA_LOCALIZACION, CalificacionOperacion::OPERACION_NO_SUJETA_OTROS]) && $this->getTipoImpuesto() === TipoImpuesto::IVA) {
            $afactura->TipoImpositivo = number_format($this->getImpuesto(), 2, '.', '');
            $afactura->CuotaRepercutida = number_format($this->getCuota(), 2, '.', '');
        }
        $afactura->BaseImponibleOimporteNoSujeto = number_format($this->getBaseImponible(), 2, '.', '');
        if($this->excenta !== null)
            $afactura->OperacionExenta = $this->excenta;
        return $afactura;
    }
}