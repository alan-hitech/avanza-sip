<?php

namespace AvanzaSip\Enums;

enum TipoRectificativa: string
{
    use BaseEnums;
    case SUSTITUCION = 'S'; /** Sempre S */

    case DIFERENCIAS= 'I';
    /**
     * Obtiene la descripción de un tipo de calificación de operación
     *
     * @return string Descripción del tipo de calificación de operación
     */
    public function getDescripcion(): string
    {
        return match ($this) {
            self::SUSTITUCION => 'Por sustitución',
            self::DIFERENCIAS => 'Por diferencias',
        };
    }
}
