<?php

namespace AvanzaSip\Enums;

enum TipoNIF: string
{
    use BaseEnums;

    case NIF = '02';
    case PASSPORT = '03';
    case DOC_OF = '04';
    case CERT = '05';
    case OTROS = '06';
    case NO_NIF = '07';

    /**
     * Obtiene la descripción
     *
     * @return string Descripción del tipo de impuesto
     */
    public function getDescripcion(): string
    {
        return match ($this) {
            self::NIF => 'NIF o IVA',
            self::PASSPORT => 'Pasaporte',
            self::DOC_OF => 'Doc. oficial',
            self::CERT => 'Certificado',
            self::OTROS => 'Otros',
            self::NO_NIF => 'No censado'
        };
    }

}