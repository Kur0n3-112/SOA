<?php
/**
 * Controlador de login:
 * - Si ya estás logueado, te manda al index.
 * - En POST: valida inputs y trata de iniciar sesión.
 * - En GET: carga inputs/errores de la sesión (PRG).
 */

// Si ya hay sesión iniciada, no tiene sentido mostrar el login
if (is_user_logged_in()) {
    redirect_to('index.php');
}

$inputs = [];
$errors = [];

if (is_post_request()) {

    // 1) Sanitiza y valida entradas de usuario
    [$inputs, $errors] = filter($_POST, [
        'username' => 'string | required',
        'password' => 'string | required'
    ]);

    // 2) Si hay errores de validación, redirige de vuelta con errores e inputs
    if ($errors) {
        redirect_with('login.php', [
            'errors' => $errors,
            'inputs' => $inputs
        ]);
    }

    // 3) Intento de login; si falla, agregar error genérico y redirigir
    if (!login($inputs['username'], $inputs['password'])) {
        $errors['login'] = 'Invalid username or password';

        redirect_with('login.php', [
            'errors' => $errors,
            'inputs' => $inputs
        ]);
    }

    // 4) Login correcto: ve al dashboard
    redirect_to('index.php');

} else if (is_get_request()) {
    // Carga inputs y errores previos (si la redirección PRG los dejó en sesión)
    [$errors, $inputs] = session_flash('errors', 'inputs');
}