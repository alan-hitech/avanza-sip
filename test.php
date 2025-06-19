<?php

require 'vendor/autoload.php';
use Dotenv\Dotenv;
use App\Classes\AvanzaSIP;
use App\Classes\Encrypt;
use App\Enums\CalificacionOperacion;
use App\Enums\OperacionExcenta;
use App\Enums\TipoFactura;
use App\Enums\TipoImpuesto;
use App\Models\Client;
use App\Models\Empresa;
use App\Models\Factura;
use App\Models\FacturaImpuesto;
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$empresa = new Empresa(
    Nombre: "ESCUELA SUPERIOR TERAPIAS NATURALES BCN, S.L.",
    CIF: "B66819186",
    RazonSocial: "ESCUELA SUPERIOR TERAPIAS NATURALES BCN, S.L.",
    certificate: $_ENV['PRIVATE_PATH'],
    Verifactu: true);
$client = new Client(NIF:'47820149K', RazonSocial: 'Alan Bertomeu Culvi');
$password = (new Encrypt(publicKey: "./public_key.pem"))->encrypt($_ENV['PRIVATE_PASSWD']);
$avanzaSIP = new AvanzaSIP(
    certificate: $empresa->certificate,
    password: $password,
    authToken: "dda7f70673a526d54f64b94faf6639f834d12328",
    test: true,
    debug: true
);
if(!$avanzaSIP->consultCompany($empresa)){
    $responseCC = $avanzaSIP->createCompany($empresa);
    var_dump($responseCC);
} else {
    $factura = new Factura(
        serie: "W",
        numFactura: "00001",
        fechaEmision: new \DateTime(),
        tipoFactura: TipoFactura::FACTURA,
        descripcion: "Servicios",
        client: $client,
        empresa: $empresa,
    );
    var_dump($avanzaSIP->consultaFactura($factura));
    die();
    $factura->setSerie("T");
    $impuesto = new FacturaImpuesto(
        tipoImpuesto: TipoImpuesto::IVA,
        regimen: "01",
        calificacionOperacion: CalificacionOperacion::OPERACION_SUJETA,
        impuesto: 21,
        baseImponible: 1000,
        cuota: 210
    );
    $factura->addImpuestoDetalle($impuesto);

    $responseInvoice = $avanzaSIP->altaFactura($factura);
    if(isset($responseInvoice->id)){
        echo $responseInvoice->id.' - PRIMEWRA FAC';
        $factura->setOriginalID($responseInvoice->id);
        $numFac = (int)$factura->getNumFactura()+1;
        $factura->setNumFactura($numFac);
        $factura->setTipoFactura(TipoFactura::RECTIFICATIVA_ART_80_UNO_DOS_SEIS);
        $impuesto->baseImponible = 100;
        $impuesto->cuota = 21;
        $factura->setImpuestosDetalle([$impuesto]);
        $responseInvoice = $avanzaSIP->altaFactura($factura);
        $responseQR = $avanzaSIP->getQR($responseInvoice->id);
    }

    $factura = new Factura(
        serie: "W",
        numFactura: "00003",
        fechaEmision: new \DateTime(),
        tipoFactura: TipoFactura::FACTURA,
        descripcion: "Servicios",
        client: $client,
        empresa: $empresa
    );
    $factura->addImpuestoDetalle(new FacturaImpuesto(
        tipoImpuesto: TipoImpuesto::IVA,
        regimen: "01",
        calificacionOperacion: CalificacionOperacion::OPERACION_NO_SUJETA_OTROS,
        impuesto: 0,
        baseImponible: 1000,
        cuota: 0,
        excenta: OperacionExcenta::EXCENTA_21
    ));
    //$responseInvoice = $avanzaSIP->altaFactura($factura);
}
