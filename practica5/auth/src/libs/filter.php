<?php
/**
 * Sanitiza y valida datos de entrada.
 * 
 * Proceso:
 * 1) Se separan las reglas de sanitización y validación por el pipe '|'
 * 2) Se sanitizan los datos (sanitize)
 * 3) Se validan los datos (validate)
 * 4) Devuelve un array con [$inputsSanitizados, $erroresDeValidación]
 *
 * @param array $data      Datos originales (ej. $_POST)
 * @param array $fields    ['campo' => 'reglasSan|reglasVal', ...]
 * @param array $messages  Mensajes personalizados
 * @return array           [$inputs, $errors]
 */
function filter(array $data, array $fields, array $messages = []): array
{
    $sanitization = []; // Reglas de sanitización por campo
    $validation = [];   // Reglas de validación por campo

    // Extrae reglas de sanitización y validación
    foreach ($fields as $field => $rules) {
        if (strpos($rules, '|')) {
            // Si hay pipe, el primer bloque es sanitización y el resto validación
            [$sanitization[$field], $validation[$field]] = explode('|', $rules, 2);
        } else {
            // Si no hay pipe, se asume solo sanitización
            $sanitization[$field] = $rules;
        }
    }

    // Ejecuta sanitización y luego validación
    $inputs = sanitize($data, $sanitization);
    $errors = validate($inputs, $validation, $messages);

    return [$inputs, $errors];
}