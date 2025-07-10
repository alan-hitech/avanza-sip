<?php

namespace AvanzaSip\Enums;

enum Regimen: string
{
    use BaseEnums;
    case GENERAL = '01'; /** Sempre S */
    case EXPORTACION= '02';
    case BIENES_USADOS = '03';
    case ORO_INVERSION = '04';
    case AGENCIAS_VIAGES = '05';
    case ENTIDADES_IVA = '06';
    case CRITERIO_CAJA = '07';
    case IGIC = '08';
    case IPSI_IGIC = '09';
    case INTRACOMUNITARIAS = '10';
    case SEGUROS = '11';
    case INMOBILIARIAS = '12';
    case FINANCIERAS = '13';
    case ARRENDAMIENTO_LOCALES = '14';
    case TRANSMISION_INMUEBLES = '15';
    /**
     * Obtiene la descripción de un tipo de calificación de operación
     *
     * @return string Descripción del tipo de calificación de operación
     */
    public function getDescripcion(): string
    {
        return match ($this) {
            self::GENERAL => 'Régimen general',
            self::EXPORTACION => 'Exportación',
            self::BIENES_USADOS => 'Bienes usados',
            self::ORO_INVERSION => 'Oro de inversión',
            self::AGENCIAS_VIAGES => 'Agencias de viajes',
            self::ENTIDADES_IVA => 'Grupo de entidades en IVA',
            self::CRITERIO_CAJA => 'Criterio de caja',
            self::IGIC => 'IGIC',
            self::IPSI_IGIC => 'IPSI/IGIC',
            self::INTRACOMUNITARIAS => 'Intracomunitarias',
            self::SEGUROS => 'Seguros',
            self::INMOBILIARIAS => 'Inmobiliarias',
            self::FINANCIERAS => 'Financieras',
            self::ARRENDAMIENTO_LOCALES => 'Arrendamiento de locales',
            self::TRANSMISION_INMUEBLES => 'Transmisiones de inmuebles',
        };
    }
}
