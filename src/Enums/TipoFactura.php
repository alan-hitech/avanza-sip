<?php

namespace App\Enums;

enum TipoFactura: string
{
    use BaseEnums;
    // Facturas normales
    case FACTURA = 'F1';
    case FACTURA_SIMPLIFICADA = 'F2';
    case FACTURA_SUSTITUCION = 'F3';

    // Facturas rectificativas
    case RECTIFICATIVA_ERROR_DERECHO = 'R1';
    case RECTIFICATIVA_ART_80_UNO_DOS_SEIS = 'R2';
    case RECTIFICATIVA_ART_80_TRES = 'R3';
    case RECTIFICATIVA_RESTO = 'R4';
    case RECTIFICATIVA_SIMPLIFICADA = 'R5';

    /**
     * Obtiene la descripción de un tipo de factura
     *
     * @return string Descripción del tipo de factura
     */
    public function getDescripcion(): string
    {
        return match ($this) {
            self::FACTURA => 'Factura',
            self::FACTURA_SIMPLIFICADA => 'Factura Simplificada',
            self::FACTURA_SUSTITUCION => 'Factura emitida en sustitución de facturas simplificadas facturadas y declaradas',
            self::RECTIFICATIVA_ERROR_DERECHO => 'Factura Rectificativa (Error fundado en derecho)',
            self::RECTIFICATIVA_ART_80_UNO_DOS_SEIS => 'Factura Rectificativa (Artículo 80 Uno, Dos y Seis)',
            self::RECTIFICATIVA_ART_80_TRES => 'Factura Rectificativa (Artículo 80 Tres)',
            self::RECTIFICATIVA_RESTO => 'Factura Rectificativa (Resto)',
            self::RECTIFICATIVA_SIMPLIFICADA => 'Factura Rectificativa en facturas simplificadas'
        };
    }

}