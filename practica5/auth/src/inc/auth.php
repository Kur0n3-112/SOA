<?php
/**
 * Funciones de autenticación y gestión de usuarios.
 * 
 * Requiere:
 * - Conexión PDO accesible a través de db()
 * - Sesión iniciada (session_start() en bootstrap.php)
 */

/**
 * Registra un nuevo usuario en la base de datos.
 *
 * @param string $email
 * @param string $username
 * @param string $password Contraseña en claro, se almacena hasheada
 * @param bool   $is_admin Flag para marcar usuarios administradores
 * @return bool True si el INSERT se ejecuta correctamente
 */
function register_user(string $email, string $username, string $password, bool $is_admin = false): bool
{
    $sql = 'INSERT INTO users(username, email, password, is_admin)
            VALUES(:username, :email, :password, :is_admin)';

    $statement = db()->prepare($sql);

    // Enlazamos parámetros con tipos estrictos
    $statement->bindValue(':username', $username, PDO::PARAM_STR);
    $statement->bindValue(':email', $email, PDO::PARAM_STR);
    // Se guarda el hash de la contraseña usando BCRYPT
    $statement->bindValue(':password', password_hash($password, PASSWORD_BCRYPT), PDO::PARAM_STR);
    $statement->bindValue(':is_admin', (int)$is_admin, PDO::PARAM_INT);

    return $statement->execute();
}

/**
 * Busca un usuario por username.
 * 
 * IMPORTANTE: Para uso en login(), también se necesita el id del usuario.
 *             Actualmente solo se selecciona username y password.
 *             Considera cambiar el SELECT a: SELECT id, username, password ...
 *
 * @param string $username
 * @return array|false Array asociativo del usuario o false si no existe
 */
function find_user_by_username(string $username)
{
    $sql = 'SELECT username, password
            FROM users
            WHERE username=:username';

    $statement = db()->prepare($sql);
    $statement->bindValue(':username', $username, PDO::PARAM_STR);
    $statement->execute();

    return $statement->fetch(PDO::FETCH_ASSOC);
}

/**
 * Intenta autenticar a un usuario con username y password.
 *
 * - Verifica el hash de la contraseña.
 * - Regenera el id de sesión para prevenir fijación de sesión.
 * - Almacena datos mínimos del usuario en $_SESSION.
 *
 * @param string $username
 * @param string $password
 * @return bool True si el login es correcto
 */
function login(string $username, string $password): bool
{
    $user = find_user_by_username($username);

    // Si se encontró usuario, verificar contraseña
    if ($user && password_verify($password, $user['password'])) {

        // Prevenir ataque de fijación de sesión
        session_regenerate_id();

        // Guardar datos en la sesión
        $_SESSION['username'] = $user['username'];

        // ATENCIÓN: $user['id'] no existe con el SELECT actual. Ver comentario en find_user_by_username()
        $_SESSION['user_id']  = $user['id'];

        return true;
    }

    return false;
}

/**
 * Devuelve true si hay un usuario autenticado.
 *
 * @return bool
 */
function is_user_logged_in(): bool
{
    return isset($_SESSION['username']);
}

/**
 * Cierra la sesión del usuario.
 *
 * - Elimina variables de sesión
 * - Destruye la sesión
 * - Redirige a login.php
 *
 * @return void
 */
function logout(): void
{
    if (is_user_logged_in()) {
        unset($_SESSION['username'], $_SESSION['user_id']);
        session_destroy();
        redirect_to('login.php');
    }
}

/**
 * Devuelve el username del usuario en sesión o null si no ha iniciado sesión.
 *
 * @return string|null
 */
function current_user()
{
    if (is_user_logged_in()) {
        return $_SESSION['username'];
    }
    return null;
}

/**
 * Requiere que el usuario esté autenticado; en caso contrario redirige a login.php.
 *
 * @return void
 */
function require_login(): void
{
    if (!is_user_logged_in()) {
        redirect_to('login.php');
    }
}