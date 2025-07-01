<?php

namespace AvanzaSip\Enums;

enum OperacionExcenta: string
{
    use BaseEnums;

    /**
     * TODO:
     * sempre E1
     */
    case EXCENTA_20 = 'E1';
    case EXCENTA_21 = 'E2';
    case EXCENTA_22 = 'E3';
    case EXCENTA_23_24 = 'E4';
    case EXCENTA_25 = 'E5';
    case EXCENTA_OTROS = 'E6';
    /**
     * Obtiene la descripción de un tipo de calificación de operación
     *
     * @return string Descripción del tipo de calificación de operación
     */
    public function getDescripcion(): string
    {
        return match ($this) {
            self::EXCENTA_20 => 'Exenta por el artículo 20',
            self::EXCENTA_21 => 'Exenta por el artículo 21',
            self::EXCENTA_22 => 'Exenta por el artículo 22',
            self::EXCENTA_23_24 => 'Exenta por los artículos 23 y 24',
            self::EXCENTA_25 => 'Exenta por el artículo 25',
            self::EXCENTA_OTROS => 'Exenta por otros',
        };
    }
}