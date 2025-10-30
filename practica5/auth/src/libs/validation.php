<?php
/**
 * Validadores y motor de validación declarativa.
 * 
 * Permite definir reglas por campo (required, email, min, max, between, same, alphanumeric, secure, unique)
 * y mensajes personalizados.
 */

const DEFAULT_VALIDATION_ERRORS = [
    'required' => 'The %s is required',
    'email' => 'The %s is not a valid email address',
    'min' => 'The %s must have at least %s characters',
    'max' => 'The %s must have at most %s characters',
    'between' => 'The %s must have between %d and %d characters',
    'same' => 'The %s must match with %s',
    'alphanumeric' => 'The %s should have only letters and numbers',
    'secure' => 'The %s must have between 8 and 64 characters and contain at least one number, one upper case letter, one lower case letter and one special character',
    'unique' => 'The %s already exists',
];

/**
 * Valida datos sanitizados según reglas declarativas.
 *
 * @param array $data      Datos sanitizados
 * @param array $fields    ['campo' => 'regla1|regla2:param|...', ...]
 * @param array $messages  Mensajes personalizados (globales o por campo/regla)
 * @return array           Array de errores por campo (vacío si no hay errores)
 */
function validate(array $data, array $fields, array $messages = []): array
{
    // Helper para dividir y trim
    $split = fn($str, $separator) => array_map('trim', explode($separator, $str));

    // Mensajes globales de reglas personalizados
    $rule_messages = array_filter($messages, fn($message) => is_string($message));
    // Sobrescribe mensajes por defecto con los globales dados
    $validation_errors = array_merge(DEFAULT_VALIDATION_ERRORS, $rule_messages);

    $errors = [];

    // Recorremos campos y sus reglas
    foreach ($fields as $field => $option) {

        $rules = $split($option, '|');

        foreach ($rules as $rule) {
            $params = [];

            // Reglas con parámetros (p.ej., min:3)
            if (strpos($rule, ':')) {
                [$rule_name, $param_str] = $split($rule, ':');
                $params = $split($param_str, ',');
            } else {
                $rule_name = trim($rule);
            }

            // Convención: función validadora is_<regla>
            $fn = 'is_' . $rule_name;

            if (is_callable($fn)) {
                $pass = $fn($data, $field, ...$params);

                if (!$pass) {
                    // Mensaje específico por campo y regla -> $messages[$field][$rule_name]
                    // o mensaje global por regla desde $validation_errors
                    $errors[$field] = sprintf(
                        $messages[$field][$rule_name] ?? $validation_errors[$rule_name],
                        $field,
                        ...$params
                    );
                }
            }
        }
    }

    return $errors;
}

/**
 * Regla: campo requerido (no vacío).
 */
function is_required(array $data, string $field): bool
{
    return isset($data[$field]) && trim($data[$field]) !== '';
}

/**
 * Regla: email válido (si está presente).
 */
function is_email(array $data, string $field): bool
{
    if (empty($data[$field])) {
        return true; // ausencia se valida con required
    }

    return filter_var($data[$field], FILTER_VALIDATE_EMAIL);
}

/**
 * Regla: longitud mínima.
 */
function is_min(array $data, string $field, int $min): bool
{
    if (!isset($data[$field])) {
        return true;
    }

    return mb_strlen($data[$field]) >= $min;
}

/**
 * Regla: longitud máxima.
 */
function is_max(array $data, string $field, int $max): bool
{
    if (!isset($data[$field])) {
        return true;
    }

    return mb_strlen($data[$field]) <= $max;
}

/**
 * Regla: longitud entre min y max (ambos inclusive).
 */
function is_between(array $data, string $field, int $min, int $max): bool
{
    if (!isset($data[$field])) {
        return true;
    }

    $len = mb_strlen($data[$field]);
    return $len >= $min && $len <= $max;
}

/**
 * Regla: igualdad entre dos campos (p.ej., password y password2).
 */
function is_same(array $data, string $field, string $other): bool
{
    if (isset($data[$field], $data[$other])) {
        return $data[$field] === $data[$other];
    }

    if (!isset($data[$field]) && !isset($data[$other])) {
        return true;
    }

    return false;
}

/**
 * Regla: alfanumérico (si está presente).
 */
function is_alphanumeric(array $data, string $field): bool
{
    if (!isset($data[$field])) {
        return true;
    }

    return ctype_alnum($data[$field]);
}

/**
 * Regla: contraseña "segura" (8-64, minúscula, mayúscula, dígito y símbolo).
 */
function is_secure(array $data, string $field): bool
{
    if (!isset($data[$field])) {
        return false;
    }

    $pattern = "#.*^(?=.{8,64})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#";
    return preg_match($pattern, $data[$field]);
}

/**
 * Regla: único en BD (tabla y columna).
 * 
 * @param array  $data
 * @param string $field   Campo a validar
 * @param string $table   Tabla a consultar
 * @param string $column  Columna a consultar
 * @return bool
 */
function is_unique(array $data, string $field, string $table, string $column): bool
{
    if (!isset($data[$field])) {
        return true;
    }

    // Consulta simple de existencia
    $sql = "SELECT $column FROM $table WHERE $column = :value";

    $stmt = db()->prepare($sql);
    $stmt->bindValue(":value", $data[$field]);

    $stmt->execute();

    // Si no devuelve filas, es único
    return $stmt->fetchColumn() === false;
}