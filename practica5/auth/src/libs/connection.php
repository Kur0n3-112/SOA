<?php
/**
 * Conecta a la base de datos y devuelve una instancia de PDO.
 * Utiliza un singleton simple (variable estática) para reutilizar la conexión.
 *
 * @return PDO
 * @throws PDOException si la conexión falla (modo ERRMODE_EXCEPTION activado)
 */
function db(): PDO
{
    static $pdo; // Se mantiene la conexión viva durante el ciclo de vida de la petición

    if (!$pdo) {
        // Construye el DSN con charset UTF8
        $pdo = new PDO(
            sprintf("mysql:host=%s;dbname=%s;charset=UTF8", DB_HOST, DB_NAME),
            DB_USER,
            DB_PASSWORD,
            // Modo de error por excepción permite capturar y manejar fallos mejor
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }

    return $pdo;
}