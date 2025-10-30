<?php
/**
 * Controlador de registro:
 * - En POST: valida y crea el usuario si todo es correcto.
 * - En GET: recupera inputs/errores de sesión (PRG).
 */

$errors = [];
$inputs = [];

if (is_post_request()) {

    // Reglas de sanitización y validación para cada campo
    $fields = [
        // username: sanitiza como string, requerido, alfanumérico, entre 3 y 25, único en users.username
        'username' => 'string | required | alphanumeric | between: 3, 25 | unique: users, username',
        // email: sanitiza y valida como email, requerido, único en users.email
        'email' => 'email | required | email | unique: users, email',
        // nombre: sanitiza como string, requerido, longitud razonable
        'nombre' => 'string | required | between: 2, 100',
        // apellidos: sanitiza como string, requerido, longitud razonable
        'apellidos' => 'string | required | between: 2, 150',
        // dni: sanitiza como string, requerido, alfanumérico, 9 caracteres (8 dígitos + 1 letra), único
        // Nota: para una validación completa de DNI/NIF podría añadirse una regla específica futura.
        'dni' => 'string | required | alphanumeric | between: 9, 9 | unique: users, dni',
        // password: sanitiza como string, requerido, debe ser "seguro"
        'password' => 'string | required | secure',
        // password2: repetición de password, debe coincidir
        'password2' => 'string | required | same: password',
        // agree: checkbox requerido (indica aceptación de términos)
        'agree' => 'string | required'
    ];

    // Mensajes personalizados por campo/regla
    $messages = [
        'password2' => [
            'required' => 'Please enter the password again',
            'same' => 'The password does not match'
        ],
        'agree' => [
            'required' => 'You need to agree to the term of services to register'
        ],
        'dni' => [
            'alphanumeric' => 'The DNI must contain only letters and numbers (e.g., 12345678Z)',
            'between' => 'The DNI must have exactly 9 characters (8 digits + 1 letter)'
        ]
    ];

    // Ejecuta sanitización + validación
    [$inputs, $errors] = filter($_POST, $fields, $messages);

    // Si hay errores, redirige de vuelta con inputs y errores (PRG)
    if ($errors) {
        redirect_with('register.php', [
            'inputs' => $inputs,
            'errors' => $errors
        ]);
    }

    // Crea el usuario; register_user internamente hashea la contraseña
    if (register_user(
        $inputs['email'],
        $inputs['username'],
        $inputs['password'],
        $inputs['nombre'],
        $inputs['apellidos'],
        $inputs['dni']
    )) {
        // Mensaje flash y redirección a login
        redirect_with_message(
            'login.php',
            'Your account has been created successfully. Please login here.'
        );
    }

} else if (is_get_request()) {
    // En GET, recupera valores previos desde la sesión para repoblar el formulario
    [$inputs, $errors] = session_flash('inputs', 'errors');
}