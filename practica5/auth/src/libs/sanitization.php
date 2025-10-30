<?php
/**
 * Definición de filtros de sanitización para distintos tipos de datos.
 * Se utilizan con filter_var_array.
 */
const FILTERS = [
    // Cadenas: escapa caracteres especiales (XSS)
    'string' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,

    // Arrays de cadenas
    'string[]' => [
        'filter' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'flags' => FILTER_REQUIRE_ARRAY
    ],

    // Emails
    'email' => FILTER_SANITIZE_EMAIL,

    // Enteros
    'int' => [
        'filter' => FILTER_SANITIZE_NUMBER_INT,
        'flags' => FILTER_REQUIRE_SCALAR
    ],
    'int[]' => [
        'filter' => FILTER_SANITIZE_NUMBER_INT,
        'flags' => FILTER_REQUIRE_ARRAY
    ],

    // Flotantes
    'float' => [
        'filter' => FILTER_SANITIZE_NUMBER_FLOAT,
        'flags' => FILTER_FLAG_ALLOW_FRACTION
    ],
    'float[]' => [
        'filter' => FILTER_SANITIZE_NUMBER_FLOAT,
        'flags' => FILTER_REQUIRE_ARRAY
    ],

    // URLs
    'url' => FILTER_SANITIZE_URL,
];

/**
 * Aplica trim recursivo a strings dentro de un array.
 *
 * @param array $items
 * @return array
 */
function array_trim(array $items): array
{
    return array_map(function ($item) {
        if (is_string($item)) {
            return trim($item);
        } elseif (is_array($item)) {
            return array_trim($item);
        } else {
            return $item;
        }
    }, $items);
}

/**
 * Sanitiza entradas según las reglas indicadas y opcionalmente hace trim.
 *
 * @param array $inputs         Datos de entrada
 * @param array $fields         Reglas por campo (usando claves de FILTERS)
 * @param int   $default_filter Filtro por defecto si no se especifican campos
 * @param array $filters        Tabla de filtros (por defecto FILTERS)
 * @param bool  $trim           Si aplicar trim recursivo al resultado
 * @return array                Datos sanitizados
 */
function sanitize(
    array $inputs,
    array $fields = [],
    int $default_filter = FILTER_SANITIZE_FULL_SPECIAL_CHARS,
    array $filters = FILTERS,
    bool $trim = true
): array {

    // Si hay reglas por campos específicos
    if ($fields) {
        // Elimina espacios sobrantes en cada definición de filtro
        $fields = array_map('trim', $fields);

        // Mapea cada nombre de filtro a su definición en $filters
        $options = array_map(fn($field) => $filters[$field], $fields);

        // Sanitiza por campo
        $data = filter_var_array($inputs, $options);
    } else {
        // Aplica el filtro por defecto a todo el array
        $data = filter_var_array($inputs, $default_filter);
    }

    // Aplica trim recursivo si se solicitó
    return $trim ? array_trim($data) : $data;
}