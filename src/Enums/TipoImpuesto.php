<?php
namespace App\Enums;

enum TipoImpuesto: string
{
    use BaseEnums;

    case IVA = '01';
    case IGIC = '02';
    case IPSI = '03';
    case OTROS = '05';

    /**
     * Obtiene la descripción de un tipo de impuesto
     *
     * @return string Descripción del tipo de impuesto
     */
    public function getDescripcion(): string
    {
        return match ($this) {
            self::IVA => 'IVA',
            self::IGIC => 'IGIC',
            self::IPSI => 'IPSI',
            self::OTROS => 'Otros'
        };
    }

}