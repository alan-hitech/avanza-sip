<?php
namespace App\Models;
use App\Enums\TipoFactura;
use DateTime;
use InvalidArgumentException;
use stdClass;

class AvanzaSIPFactura
{
    public AvanzaSIPEmpresa $empresa;
    public string $serie;
    public string $numFactura;
    public DateTime $fechaEmision;
    public TipoFactura $tipoFactura;
    public string $descripcion;
    public AvanzaSIPClient $client;
    public float $importe;
    public float $impuestos;
    public array $impuestosDetalle;

    /**
     * @return string
     */
    public function getNumFactura(): string
    {
        return $this->numFactura;
    }

    /**
     * @param string $numFactura
     */
    public function setNumFactura(string $numFactura): void
    {
        $this->numFactura = $numFactura;
    }

    /**
     * @return DateTime
     */
    public function getFechaEmision(): DateTime
    {
        return $this->fechaEmision;
    }

    /**
     * @param DateTime $fechaEmision
     */
    public function setFechaEmision(DateTime $fechaEmision): void
    {
        $this->fechaEmision = $fechaEmision;
    }

    /**
     * @return string
     */
    public function getTipoFactura(): TipoFactura
    {
        return $this->tipoFactura;
    }

    /**
     * @param string $tipoFactura
     */
    public function setTipoFactura(TipoFactura $tipoFactura): void
    {
        $this->tipoFactura = $tipoFactura;
    }

    /**
     * @return string
     */
    public function getDescripcion(): string
    {
        return $this->descripcion;
    }

    /**
     * @param string $descripcion
     */
    public function setDescripcion(string $descripcion): void
    {
        $this->descripcion = $descripcion;
    }

    /**
     * @return AvanzaSIPClient
     */
    public function getClient(): AvanzaSIPClient
    {
        return $this->client;
    }

    /**
     * @param AvanzaSIPClient $client
     */
    public function setClient(AvanzaSIPClient $client): void
    {
        $this->client = $client;
    }

    /**
     * @return float
     */
    public function getImporte(): float
    {
        return $this->importe;
    }

    /**
     * @param float $importe
     */
    public function setImporte(float $importe): void
    {
        $this->importe = $importe;
    }

    /**
     * @return float
     */
    public function getImpuestos(): float
    {
        return $this->impuestos;
    }

    /**
     * @param float $impuestos
     */
    public function setImpuestos(float $impuestos): void
    {
        $this->impuestos = $impuestos;
    }

    /**
     * @return array
     */
    public function getImpuestosDetalle(): array
    {
        return $this->impuestosDetalle;
    }

    /**
     * @param array $impuestosDetalle
     */
    public function setImpuestosDetalle(array $impuestosDetalle): void
    {
        foreach ($impuestosDetalle as $detalle) {
            if (!$detalle instanceof AvanzaSIPFacturaImpuesto) {
                throw new InvalidArgumentException(
                    "Todos los elementos de impuestosDetalle deben ser instancias de AvanzaSIPFacturaImpuesto."
                );
            }
        }
        $this->impuestosDetalle = $impuestosDetalle;
    }

    public function addImpuestoDetalle(AvanzaSIPFacturaImpuesto $impuestoDetalle): void
    {
        $this->impuestosDetalle[] = $impuestoDetalle;
    }
    private function calcular():void
    {
        $this->importe = 0;
        $this->impuestos = 0;
        foreach ($this->impuestosDetalle as $detalle) {
            $this->importe += $detalle->getBaseImponible() + $detalle->getCuota();
            $this->impuestos += $detalle->getCuota();
        }
    }
    /**
     * @return string
     */
    public function getSerie(): string
    {
        return $this->serie;
    }

    /**
     * @param string $serie
     */
    public function setSerie(string $serie): void
    {
        $this->serie = $serie;
    }

    public function __construct(string $serie, string $numFactura, DateTime $fechaEmision, TipoFactura $tipoFactura, string $descripcion, AvanzaSIPClient $client, AvanzaSIPEmpresa $empresa)
    {
        $this->serie = $serie;
        $this->numFactura = $numFactura;
        $this->fechaEmision = $fechaEmision;
        $this->tipoFactura = $tipoFactura;
        $this->descripcion = $descripcion;
        $this->client = $client;
        $this->empresa = $empresa;
    }

    public function toAlta(string $password, ?string$edit=null): \stdClass
    {
        $data = new stdClass();
       // $data->Password = new AvanzaSIPEncrypt()->encrypt($password);
        $data->serie = $this->serie;
        if($edit!==null)
            $data->id = $edit;
        $data->numero = $this->numFactura;
        $data->Cabecera = new stdClass();
        $data->Cabecera->NIF = $this->empresa->CIF;
        $data->Cabecera->ObligadoEmision = new stdClass();
        $data->Cabecera->ObligadoEmision->NombreRazon = $this->empresa->RazonSocial;
        $data->RegistroFactura = new stdClass();
        $data->RegistroFactura->RegistroAlta = new stdClass();
        $data->RegistroFactura->RegistroAlta->IDFactura = new stdClass();
        $data->RegistroFactura->RegistroAlta->IDFactura->NumSerieFactura = $this->serie . $this->numFactura;
        $data->RegistroFactura->RegistroAlta->IDFactura->FechaExpedicionFactura = $this->fechaEmision->format('d-m-Y');;
        $data->RegistroFactura->RegistroAlta->TipoFactura = $this->tipoFactura;
        $data->RegistroFactura->RegistroAlta->DescripcionOperacion = $this->descripcion;
        $data->RegistroFactura->RegistroAlta->Destinatarios = new stdClass();
        $data->RegistroFactura->RegistroAlta->Destinatarios->IDDestinatario = new stdClass();
        $data->RegistroFactura->RegistroAlta->Destinatarios->IDDestinatario->NIF = $this->client->NIF;
        $data->RegistroFactura->RegistroAlta->Destinatarios->IDDestinatario->NombreRazon = $this->client->RazonSocial;
        $data->RegistroFactura->RegistroAlta->ImporteTotal = 0;
        $data->RegistroFactura->RegistroAlta->CuotaTotal = 0;
        $data->RegistroFactura->RegistroAlta->DetalleDesglose = [];
        foreach ($this->impuestosDetalle as $detalle) {
            $data->RegistroFactura->RegistroAlta->ImporteTotal += $detalle->getBaseImponible() + $detalle->getCuota();
            $data->RegistroFactura->RegistroAlta->CuotaTotal += $detalle->getCuota();
            $data->RegistroFactura->RegistroAlta->DetalleDesglose[] = $detalle->aFactura();
        }
        $data->RegistroFactura->RegistroAlta->ImporteTotal = number_format($data->RegistroFactura->RegistroAlta->ImporteTotal, 2,'.',"");
        $data->RegistroFactura->RegistroAlta->CuotaTotal = number_format($data->RegistroFactura->RegistroAlta->CuotaTotal, 2,'.',"");
        var_dump($data);
        return $data;
    }

    public function toEdit($password, $idFacturaAntigua): \stdClass
    {
        $data = $this->toAlta($password, $idFacturaAntigua);
        return $data;
    }
}
