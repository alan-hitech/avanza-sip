# TipoFactura Enum para PHP 8.2

Este repositorio contiene una implementación de un enum para tipos de factura en PHP 8.2.

## Descripción

El enum `TipoFactura` reemplaza la implementación anterior basada en constantes de clase en `AvanzaSIPFactura.php`. Proporciona una forma más robusta y tipada de manejar los diferentes tipos de factura.

## Tipos de Factura Incluidos

- **F1**: Factura
- **F2**: Factura Simplificada
- **F3**: Factura emitida en sustitución de facturas simplificadas facturadas y declaradas
- **R1**: Factura Rectificativa (Error fundado en derecho)
- **R2**: Factura Rectificativa (Artículo 80 Uno, Dos y Seis)
- **R3**: Factura Rectificativa (Artículo 80 Tres)
- **R4**: Factura Rectificativa (Resto)
- **R5**: Factura Rectificativa en facturas simplificadas

## Uso

### Obtener un tipo de factura

```php
// Acceder directamente a un caso del enum
use enums\TipoFactura;$tipoFactura = TipoFactura::FACTURA;

// Obtener un tipo de factura a partir de su valor (código)
$tipoFactura = TipoFactura::fromValue('F1');
```

### Obtener la descripción de un tipo de factura

```php
// Usando un caso del enum
use enums\TipoFactura;$descripcion = TipoFactura::FACTURA->getDescripcion();

// Usando un valor obtenido dinámicamente
$tipoFactura = TipoFactura::fromValue('F1');
if ($tipoFactura) {
    $descripcion = $tipoFactura->getDescripcion();
}
```

### Obtener todos los tipos de factura

```php
use enums\TipoFactura;$tiposFactura = TipoFactura::getTiposFactura();
// Devuelve un array asociativo con códigos y descripciones
```

### Verificar si un código de tipo de factura es válido

```php
use enums\TipoFactura;$esValido = TipoFactura::esValido('F1'); // true
$esValido = TipoFactura::esValido('X9'); // false
```

## Migración desde la implementación anterior

Si estabas utilizando la clase `AvanzaSIPFactura`, aquí hay una guía para migrar a la nueva implementación:

| Implementación Anterior | Nueva Implementación |
|-------------------------|----------------------|
| `AvanzaSIPFactura::FACTURA` | `TipoFactura::FACTURA->value` |
| `AvanzaSIPFactura::getDescripcion('F1')` | `TipoFactura::fromValue('F1')?->getDescripcion()` o `TipoFactura::FACTURA->getDescripcion()` |
| `AvanzaSIPFactura::getTiposFactura()` | `TipoFactura::getTiposFactura()` |
| `AvanzaSIPFactura::esValido('F1')` | `TipoFactura::esValido('F1')` |

## Requisitos

- PHP 8.2 o superior