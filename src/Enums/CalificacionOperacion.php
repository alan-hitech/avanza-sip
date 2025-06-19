<?php

namespace App\Enums;

enum CalificacionOperacion: string
{
    use BaseEnums;
    // OPERACIONES SUJETAS
    case OPERACION_SUJETA = 'S1';
    case OPERACION_SUJETA_PASIVO = 'S2';

    // OPERACIONES NO SUJETAS
    case OPERACION_NO_SUJETA_LOCALIZACION = 'N1';
    case OPERACION_NO_SUJETA_OTROS = 'N2';
    /**
     * Obtiene la descripción de un tipo de calificación de operación
     *
     * @return string Descripción del tipo de calificación de operación
     */
    public function getDescripcion(): string
    {
        return match ($this) {
            self::OPERACION_SUJETA => 'Operación sujeta',
            self::OPERACION_SUJETA_PASIVO => 'Operación sujeta por inversión del sujeto pasivo',
            self::OPERACION_NO_SUJETA_LOCALIZACION => 'Operación no sujeta en el TAI por reglas de localización',
            self::OPERACION_NO_SUJETA_OTROS => 'Operación no sujeta - Otros'
        };
    }
}
