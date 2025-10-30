<?php
/**
 * Sistema de mensajes flash:
 * - Permite mostrar mensajes temporales (éxito, error, info, etc.) entre redirecciones.
 */

const FLASH = 'FLASH_MESSAGES'; // Clave contenedora de mensajes en $_SESSION

// Tipos de mensajes soportados
const FLASH_ERROR = 'error';
const FLASH_WARNING = 'warning';
const FLASH_INFO = 'info';
const FLASH_SUCCESS = 'success';

/**
 * Crea o reemplaza un mensaje flash identificado por $name.
 *
 * @param string $name     Identificador único del mensaje
 * @param string $message  Texto del mensaje
 * @param string $type     Tipo (error, warning, info, success)
 * @return void
 */
function create_flash_message(string $name, string $message, string $type): void
{
    // Si existe un mensaje con el mismo nombre, se elimina para evitar duplicados
    if (isset($_SESSION[FLASH][$name])) {
        unset($_SESSION[FLASH][$name]);
    }
    // Almacena el mensaje con tipo en la sesión
    $_SESSION[FLASH][$name] = ['message' => $message, 'type' => $type];
}

/**
 * Devuelve el HTML formateado para un mensaje flash.
 *
 * @param array $flash_message ['message' => string, 'type' => string]
 * @return string HTML del mensaje
 */
function format_flash_message(array $flash_message): string
{
    return sprintf(
        '<div class="alert alert-%s">%s</div>',
        $flash_message['type'],
        $flash_message['message']
    );
}

/**
 * Muestra y elimina un mensaje flash por nombre.
 *
 * @param string $name
 * @return void
 */
function display_flash_message(string $name): void
{
    if (!isset($_SESSION[FLASH][$name])) {
        return;
    }

    // Obtiene el mensaje y lo elimina de la sesión (solo se muestra una vez)
    $flash_message = $_SESSION[FLASH][$name];
    unset($_SESSION[FLASH][$name]);

    echo format_flash_message($flash_message);
}

/**
 * Muestra y elimina todos los mensajes flash disponibles.
 *
 * @return void
 */
function display_all_flash_messages(): void
{
    if (!isset($_SESSION[FLASH])) {
        return;
    }

    // Copia mensajes y limpia sesión
    $flash_messages = $_SESSION[FLASH];
    unset($_SESSION[FLASH]);

    // Muestra cada mensaje
    foreach ($flash_messages as $flash_message) {
        echo format_flash_message($flash_message);
    }
}

/**
 * API unificada para trabajar con mensajes flash:
 * - Si se pasan $name, $message y $type: se crea el mensaje.
 * - Si se pasa solo $name: se muestra ese mensaje.
 * - Si no se pasa nada: muestra todos los mensajes.
 *
 * @param string $name
 * @param string $message
 * @param string $type
 * @return void
 */
function flash(string $name = '', string $message = '', string $type = ''): void
{
    if ($name !== '' && $message !== '' && $type !== '') {
        // Crea un mensaje flash
        create_flash_message($name, $message, $type);
    } elseif ($name !== '' && $message === '' && $type === '') {
        // Muestra un mensaje por nombre
        display_flash_message($name);
    } elseif ($name === '' && $message === '' && $type === '') {
        // Muestra todos los mensajes
        display_all_flash_messages();
    }
}