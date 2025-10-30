<?php
/**
 * Página de inicio de sesión
 * - Muestra el formulario de login.
 * - La lógica de procesamiento (POST) vive en src/login.php.
 */

require __DIR__ . '/../src/bootstrap.php';  // Inicializa sesión, helpers, etc.
require __DIR__ . '/../src/login.php';      // Controlador: maneja POST/GET, validación y redirecciones
?>

<?php view('header', ['title' => 'Login']) ?>

<?php if (isset($errors['login'])) : ?>
    <!-- Muestra un error general de login si usuario/contraseña son inválidos -->
    <div class="alert alert-error">
        <?= $errors['login'] ?>
    </div>
<?php endif ?>

<!-- Formulario de inicio de sesión.
     - method="post" envía al mismo archivo login.php que es gestionado por src/login.php -->
<form action="login.php" method="post">
    <h1>Login</h1>

    <div>
        <label for="username">Username:</label>
        <!-- Se repuebla el campo en caso de error usando $inputs -->
        <input type="text" name="username" id="username" value="<?= $inputs['username'] ?? '' ?>">
        <small><?= $errors['username'] ?? '' ?></small>
    </div>

    <div>
        <label for="password">Password:</label>
        <!-- No se repuebla la contraseña por seguridad -->
        <input type="password" name="password" id="password">
        <small><?= $errors['password'] ?? '' ?></small>
    </div>

    <section>
        <button type="submit">Login</button>
        <!-- Enlace para ir al registro -->
        <a href="register.php">Register</a>
    </section>
</form>

<?php view('footer') ?>