<?php
/**
 * Dashboard (página protegida)
 * - Requiere que el usuario esté autenticado.
 * - Muestra un saludo y un enlace para cerrar sesión.
 */

require __DIR__ . '/../src/bootstrap.php'; // Carga librerías, helpers, conexión DB, etc.
require_login(); // Redirige a login.php si el usuario no ha iniciado sesión
?>

<?php view('header', ['title' => 'Dashboard']) ?>
    <!-- current_user() devuelve el nombre de usuario guardado en la sesión -->
    <p>Welcome <?= current_user() ?> <a href="logout.php">Logout</a></p>
<?php view('footer') ?>