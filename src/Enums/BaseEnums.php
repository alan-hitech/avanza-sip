<?php

namespace App\Enums;

use App\Enums\Enum;

trait BaseEnums
{
    
    /**
     * Verifica si un tipo de impuesto es válido
     *
     * @param string $item Código del tipo de impuesto
     * @return bool True si es válido, false en caso contrario
     */
    public static function esValido(string $item): bool
    {
        foreach (self::cases() as $case) {
            if ($case->value === $item) {
                return true;
            }
        }
        return false;
    }

    /**
     * Obtiene un caso de enum a partir de su valor
     *
     * @param string $value Valor del enum
     * @return Enum|null Instancia del enum o null si no existe
     */
    public static function fromValue(string $value): ?self
    {
        foreach (self::cases() as $case) {
            if ($case->value === $value) {
                return $case;
            }
        }
        return null;
    }
}
