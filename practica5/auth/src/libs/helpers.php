<?php
/**
 * Conjunto de funciones de ayuda (helpers) para vistas, redirecciones y sesión.
 */

/**
 * Renderiza una vista (archivo en src/inc/) e inyecta variables del array $data.
 *
 * @param string $filename Nombre del archivo de vista (sin extensión)
 * @param array  $data     Variables a extraer para la vista
 * @return void
 */
function view(string $filename, array $data = []): void
{
    // Crea variables a partir de las claves del array asociativo
    foreach ($data as $key => $value) {
        $$key = $value;
    }
    // Incluye la vista desde src/inc/
    require_once __DIR__ . '/../inc/' . $filename . '.php';
}

/**
 * Devuelve la clase 'error' si existe un error para el campo indicado.
 *
 * @param array  $errors
 * @param string $field
 * @return string
 */
function error_class(array $errors, string $field): string
{
    return isset($errors[$field]) ? 'error' : '';
}

/**
 * Devuelve true si el método HTTP es POST.
 *
 * @return boolean
 */
function is_post_request(): bool
{
    return strtoupper($_SERVER['REQUEST_METHOD']) === 'POST';
}

/**
 * Devuelve true si el método HTTP es GET.
 *
 * @return boolean
 */
function is_get_request(): bool
{
    return strtoupper($_SERVER['REQUEST_METHOD']) === 'GET';
}

/**
 * Redirige a otra URL y termina la ejecución.
 *
 * @param string $url
 * @return void
 */
function redirect_to(string $url): void
{
    header('Location:' . $url);
    exit;
}

/**
 * Redirige a una URL guardando datos en $_SESSION con las claves proporcionadas.
 * Útil para persistir inputs y errores en redirecciones Post/Redirect/Get (PRG).
 *
 * @param string $url
 * @param array  $items ['clave' => $valor, ...]
 */
function redirect_with(string $url, array $items): void
{
    foreach ($items as $key => $value) {
        $_SESSION[$key] = $value;
    }

    redirect_to($url);
}

/**
 * Redirige a una URL mostrando un mensaje flash.
 *
 * @param string $url
 * @param string $message
 * @param string $type
 */
function redirect_with_message(string $url, string $message, string $type = FLASH_SUCCESS)
{
    flash('flash_' . uniqid(), $message, $type);
    redirect_to($url);
}

/**
 * Recupera y elimina de la sesión un conjunto de claves, devolviendo un array con sus valores.
 * Si una clave no existe, devuelve un array vacío en su posición.
 *
 * @param mixed ...$keys Claves a recuperar de $_SESSION
 * @return array
 */
function session_flash(...$keys): array
{
    $data = [];
    foreach ($keys as $key) {
        if (isset($_SESSION[$key])) {
            $data[] = $_SESSION[$key];
            unset($_SESSION[$key]);
        } else {
            $data[] = [];
        }
    }
    return $data;
}