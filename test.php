<?php

require __DIR__ . '/vendor/autoload.php';
use Dotenv\Dotenv;
use AvanzaSip\Classes\AvanzaSIP;
use AvanzaSip\Classes\Encrypt;
use AvanzaSip\Enums\CalificacionOperacion;
use AvanzaSip\Enums\OperacionExcenta;
use AvanzaSip\Enums\TipoFactura;
use AvanzaSip\Enums\TipoImpuesto;
use AvanzaSip\Enums\TipoRectificativa;
use AvanzaSip\Models\Client;
use AvanzaSip\Models\Empresa;
use AvanzaSip\Models\Factura;
use AvanzaSip\Models\FacturaImpuesto;
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
    $rectifica = new Factura(
        serie: "WW",
        numFactura: "00001",
        fechaEmision: (new \DateTime())->setDate(2025,6,2),
        tipoFactura: TipoFactura::FACTURA,
        descripcion: "Rectificación Factura de formación",
        client: $client,
        empresa: $empresa,
    );
    $rectificaIm = new FacturaImpuesto(
        tipoImpuesto: TipoImpuesto::IVA,
        regimen: "01",
        calificacionOperacion: CalificacionOperacion::OPERACION_SUJETA,
        impuesto: 21,
        baseImponible: 1000,
        cuota: 210
    );
    $rectifica->addImpuestoDetalle($rectificaIm);
    //$avanzaSIP->altaFactura($rectifica);
    $factura = new Factura(
        serie: "RW",
        numFactura: "00001",
        fechaEmision: (new \DateTime())->setDate(2025,6,19),
        tipoFactura: TipoFactura::RECTIFICATIVA_ART_80_UNO_DOS_SEIS,
        descripcion: "Servicios",
        client: $client,
        empresa: $empresa,
    );
    $impuesto = new FacturaImpuesto(
        tipoImpuesto: TipoImpuesto::IVA,
        regimen: "01",
        calificacionOperacion: CalificacionOperacion::OPERACION_SUJETA,
        impuesto: 21,
        baseImponible: 100,
        cuota: 21
    );
    $factura->addImpuestoDetalle($impuesto);
    $responseInvoice = $avanzaSIP->rectificativa($factura, TipoRectificativa::SUSTITUCION, $rectifica);
    var_dump($responseInvoice);
    return;
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
