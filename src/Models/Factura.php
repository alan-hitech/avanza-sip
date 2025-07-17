<?php
namespace AvanzaSip\Models;
use AvanzaSip\Enums\TipoFactura;
use AvanzaSip\Enums\TipoRectificativa;
use DateTime;
use InvalidArgumentException;
use stdClass;

class Factura
{
    public Empresa $empresa;
    public string $serie;
    public string $numFactura;
    public DateTime $fechaEmision;
    public TipoFactura $tipoFactura;
    public string $descripcion;
    public Client $client;
    public float $importe;
    public float $baseImponible;
    public float $impuestos;
    public array $impuestosDetalle;
    public ?string $originalID;
    public ?TipoRectificativa $tipoRectificativa = null;
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
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * @param Client $client
     */
    public function setClient(Client $client): void
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

    public function getTipoRectificativa(): ?TipoRectificativa
    {
        return $this->tipoRectificativa;
    }

    public function setTipoRectificativa(?TipoRectificativa $tipoRectificativa): void
    {
        $this->tipoRectificativa = $tipoRectificativa;
    }


    /**
     * @param array $impuestosDetalle
     */
    public function setImpuestosDetalle(array $impuestosDetalle): void
    {
        foreach ($impuestosDetalle as $detalle) {
            if (!$detalle instanceof FacturaImpuesto) {
                throw new InvalidArgumentException(
                    "Todos los elementos de impuestosDetalle deben ser instancias de FacturaImpuesto."
                );
            }
        }
        $this->impuestosDetalle = $impuestosDetalle;
    }

    public function addImpuestoDetalle(FacturaImpuesto $impuestoDetalle): void
    {
        $this->impuestosDetalle[] = $impuestoDetalle;
    }
    private function calcular():void
    {
        $this->baseImponible = 0;
        $this->impuestos = 0;
        foreach ($this->impuestosDetalle as $detalle) {

            $this->baseImponible += $detalle->getBaseImponible();
            $this->impuestos += $detalle->getCuota();
        }
        $this->importe = $this->baseImponible + $this->impuestos;
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

    public function getOriginalID(): string
    {
        return $this->originalID;
    }

    public function setOriginalID(?string $originalID): void
    {
        $this->originalID = $originalID;
    }

    public function getEmpresa(): ?Empresa
    {
        return $this->empresa;
    }

    public function setEmpresa(Empresa $empresa): void
    {
        $this->empresa = $empresa;
    }

    public function __construct(string $serie, string $numFactura, DateTime $fechaEmision, TipoFactura $tipoFactura, string $descripcion, Client $client, Empresa $empresa, ?string $originalID = null)
    {
        $this->serie = $serie;
        $this->numFactura = $numFactura;
        $this->fechaEmision = $fechaEmision;
        $this->tipoFactura = $tipoFactura;
        $this->descripcion = $descripcion;
        $this->client = $client;
        $this->empresa = $empresa;
        $this->originalID = $originalID;
    }

    public function getId(): string
    {
        return "{$this->empresa->CIF}-{$this->serie}-{$this->numFactura}";
    }
    public function toQR():\stdClass
    {
        $data = new \stdClass();
        $data->id = $this->getId();
        return $data;
    }
    public function toAlta(?bool $edit = false): \stdClass
    {
        $data = new stdClass();
       // $data->Password = new Encrypt()->encrypt($password);
        $data->serie = $this->serie;
        if($this->originalID !== null)
            $data->id = $this->originalID;
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
        $data->RegistroFactura->RegistroAlta->ImporteTotal = number_format($data->RegistroFactura->RegistroAlta->ImporteTotal+20, 2,'.',"");
        $data->RegistroFactura->RegistroAlta->CuotaTotal = number_format($data->RegistroFactura->RegistroAlta->CuotaTotal, 2,'.',"");
        return $data;
    }

    public function toEdit(): \stdClass
    {
        return $this->toAlta(edit: true);
    }

    public function toRectificativa(Factura $rectificada): \stdClass{
        $data = new stdClass();
        $data->serie = $this->serie;
        $data->numero = $this->numFactura;
        $data->Cabecera = new stdClass();
        $data->Cabecera->NIF = $this->empresa->CIF;
        $data->Cabecera->ObligadoEmision = new stdClass();
        $data->Cabecera->ObligadoEmision->NombreRazon = $this->empresa->RazonSocial;
        $data->RegistroFactura = new stdClass();
        $data->RegistroFactura->RegistroAlta = new stdClass();
        $data->RegistroFactura->RegistroAlta->IDFactura = new stdClass();
        $data->RegistroFactura->RegistroAlta->IDFactura->IDEmisorFactura = $this->empresa->CIF;
        $data->RegistroFactura->RegistroAlta->IDFactura->NumSerieFactura = $this->serie . $this->numFactura;
        $data->RegistroFactura->RegistroAlta->IDFactura->FechaExpedicionFactura = $this->fechaEmision->format('d-m-Y');;
        $data->RegistroFactura->RegistroAlta->NombreRazonEmisor = $this->empresa->RazonSocial;
        $data->RegistroFactura->RegistroAlta->TipoFactura = $this->tipoFactura;
        $data->RegistroFactura->RegistroAlta->TipoRectificativa = $this->tipoRectificativa;
        $data->RegistroFactura->RegistroAlta->Destinatarios = new stdClass();
        $data->RegistroFactura->RegistroAlta->Destinatarios->IDDestinatario = new stdClass();
        $data->RegistroFactura->RegistroAlta->Destinatarios->IDDestinatario->NIF = $this->client->NIF;
        $data->RegistroFactura->RegistroAlta->Destinatarios->IDDestinatario->NombreRazon = $this->client->RazonSocial;
        $data->RegistroFactura->RegistroAlta->FacturasRectificadas = new stdClass();
        $data->RegistroFactura->RegistroAlta->FacturasRectificadas->IDFacturaRectificada = [];
        $facRec = new stdClass();
        $facRec->IDEmisorFactura = $rectificada->empresa->CIF;
        $facRec->NumSerieFactura = $rectificada->serie . $rectificada->numFactura;
        $facRec->FechaExpedicionFactura = $rectificada->fechaEmision->format('d-m-Y');;
        $data->RegistroFactura->RegistroAlta->ImporteRectificacion = new stdClass();
        $data->RegistroFactura->RegistroAlta->FacturasRectificadas->IDFacturaRectificada[] = $facRec;
        $rectificada->calcular();
        if(TipoRectificativa::SUSTITUCION === $this->tipoRectificativa) {
            $data->RegistroFactura->RegistroAlta->ImporteRectificacion->BaseRectificada = number_format($rectificada->baseImponible, 2, '.', "");
            $data->RegistroFactura->RegistroAlta->ImporteRectificacion->CuotaRectificada = number_format($rectificada->impuestos, 2, '.', "");
        }
        $data->RegistroFactura->RegistroAlta->DescripcionOperacion = $this->descripcion;
        $data->RegistroFactura->RegistroAlta->ImporteTotal = 0;
        $data->RegistroFactura->RegistroAlta->CuotaTotal = 0;
        foreach ($this->impuestosDetalle as $detalle) {
            $data->RegistroFactura->RegistroAlta->ImporteTotal += $detalle->getBaseImponible() + $detalle->getCuota();
            $data->RegistroFactura->RegistroAlta->CuotaTotal += $detalle->getCuota();
            $data->RegistroFactura->RegistroAlta->DetalleDesglose[] = $detalle->aFactura();
        }
        $data->RegistroFactura->RegistroAlta->ImporteTotal = number_format($data->RegistroFactura->RegistroAlta->ImporteTotal, 2,'.',"");
        $data->RegistroFactura->RegistroAlta->CuotaTotal = number_format($data->RegistroFactura->RegistroAlta->CuotaTotal, 2,'.',"");
        
        return $data;
    }
    public function toConsulta(): \stdClass
    {
        $data = new stdClass();
        $data->id = $this->getId();
        return $data;

    }
}
