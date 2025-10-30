<?php
/**
 * Bootstrap de la aplicación:
 * - Inicia sesión
 * - Carga configuración y librerías compartidas
 * - Expone funciones helper y de autenticación
 */

// Inicia la sesión PHP (requerido para flash messages y login)
session_start();

// Configuración de base de datos
require_once __DIR__ . '/../config/database.php';

// Helpers y utilidades
require_once __DIR__ . '/libs/helpers.php';
require_once __DIR__ . '/libs/flash.php';

// Sanitización y validación
require_once __DIR__ . '/libs/sanitization.php';
require_once __DIR__ . '/libs/validation.php';
require_once __DIR__ . '/libs/filter.php';

// Conexión a base de datos
require_once __DIR__ . '/libs/connection.php';

// Funciones de autenticación
require_once __DIR__ . '/inc/auth.php';