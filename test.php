<?php

require 'vendor/autoload.php';

use App\Classes\AvanzaSIP;
use App\Classes\AvanzaSIPEncrypt;
use App\Enums\CalificacionOperacion;
use App\Enums\OperacionExcenta;
use App\Enums\TipoFactura;
use App\Enums\TipoImpuesto;
use App\Models\AvanzaSIPClient;
use App\Models\AvanzaSIPEmpresa;
use App\Models\AvanzaSIPFactura;
use App\Models\AvanzaSIPFacturaImpuesto;

$empresa = new AvanzaSIPEmpresa(
    Nombre: "ESCUELA SUPERIOR TERAPIAS NATURALES BCN, S.L.",
    CIF: "B66819186",
    RazonSocial: "ESCUELA SUPERIOR TERAPIAS NATURALES BCN, S.L.",
    certificate: './47827843X_XIN_YUE_CALDUCH__R__B66819186_.p12',
    Verifactu: true);
$client = new AvanzaSIPClient(NIF:'47820149K', RazonSocial: 'Alan Bertomeu Culvi');
$password = (new AvanzaSIPEncrypt(publicKey: "./public_key.pem"))->encrypt("123456");
$avanzaSIP = new AvanzaSIP(
    certificate: $empresa->certificate,
    password: $password,
    authToken: "dda7f70673a526d54f64b94faf6639f834d12328",
    test: true
);
if(!$avanzaSIP->consultCompany($empresa->toConsulta())){
    $responseCC = $avanzaSIP->createCompany($empresa->toCreate());
    var_dump($responseCC);
} else {
    $factura = new AvanzaSIPFactura(
        serie: "R",
        numFactura: "00001",
        fechaEmision: new \DateTime(),
        tipoFactura: TipoFactura::RECTIFICATIVA_ART_80_UNO_DOS_SEIS,
        descripcion: "Servicios",
        client: $client,
        empresa: $empresa
    );
    $factura->addImpuestoDetalle(new AvanzaSIPFacturaImpuesto(
        tipoImpuesto: TipoImpuesto::IVA,
        regimen: "01",
        calificacionOperacion: CalificacionOperacion::OPERACION_SUJETA,
        impuesto: 21,
        baseImponible: 10,
        cuota: 2.1
    ));

    $factura->addImpuestoDetalle(new AvanzaSIPFacturaImpuesto(
        tipoImpuesto: TipoImpuesto::IVA,
        regimen: "01",
        calificacionOperacion: CalificacionOperacion::OPERACION_SUJETA,
        impuesto: 10,
        baseImponible: 10,
        cuota: 1
    ));
    $responseInvoice = $avanzaSIP->editFactura($factura, "B66819186-W-00001");
    if(isset($responseInvoice->id)){
        $responseQR = $avanzaSIP->getQR($responseInvoice->id);
    }

    $factura = new AvanzaSIPFactura(
        serie: "W",
        numFactura: "00003",
        fechaEmision: new \DateTime(),
        tipoFactura: TipoFactura::FACTURA,
        descripcion: "Servicios",
        client: $client,
        empresa: $empresa
    );
    $factura->addImpuestoDetalle(new AvanzaSIPFacturaImpuesto(
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
