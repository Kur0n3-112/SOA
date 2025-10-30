<?php
/**
 * Endpoint para cerrar sesión.
 * - Llama a logout() que destruye la sesión y redirige a login.php.
 */

require __DIR__ . '/../src/bootstrap.php'; // Carga funciones, incluyendo logout()
logout();                                   // El propio logout hace redirect_to('login.php')