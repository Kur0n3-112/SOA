<?php
/**
 * Página de registro de usuarios
 * - Muestra el formulario de registro.
 * - La lógica de validación/creación vive en src/register.php.
 */
require __DIR__ . '/../src/bootstrap.php';   // Inicializa sesión y helpers
require __DIR__ . '/../src/register.php';    // Controlador de registro (maneja POST/GET)
?>

<?php view('header', ['title' => 'Register']) ?>

<!-- Formulario de registro.
     - action="register.php" envía a este mismo endpoint, gestionado por src/register.php -->
<form action="register.php" method="post">
    <h1>Sign Up</h1>

    <div>
        <label for="username">Username:</label>
        <!-- value repuebla el input tras error; error_class() añade clase CSS 'error' si hay error -->
        <input type="text" name="username" id="username" value="<?= $inputs['username'] ?? '' ?>"
               class="<?= error_class($errors, 'username') ?>">
        <small><?= $errors['username'] ?? '' ?></small>
    </div>

    <div>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="<?= $inputs['email'] ?? '' ?>"
               class="<?= error_class($errors, 'email') ?>">
        <small><?= $errors['email'] ?? '' ?></small>
    </div>

    <div>
        <label for="password">Password:</label>
        <!-- Por seguridad, no debería repoblarse la contraseña; se mantiene tal cual el ejemplo -->
        <input type="password" name="password" id="password" value="<?= $inputs['password'] ?? '' ?>"
               class="<?= error_class($errors, 'password') ?>">
        <small><?= $errors['password'] ?? '' ?></small>
    </div>

    <div>
        <label for="password2">Password Again:</label>
        <input type="password" name="password2" id="password2" value="<?= $inputs['password2'] ?? '' ?>"
               class="<?= error_class($errors, 'password2') ?>">
        <small><?= $errors['password2'] ?? '' ?></small>
    </div>

    <div>
        <!-- El checkbox se marca si en $inputs['agree'] hay 'checked' -->
        <label for="agree">
            <input type="checkbox" name="agree" id="agree" value="checked" <?= $inputs['agree'] ?? '' ?> /> I
            agree
            with the
            <a href="#" title="term of services">term of services</a>
        </label>
        <small><?= $errors['agree'] ?? '' ?></small>
    </div>

    <button type="submit">Register</button>

    <footer>Already a member? <a href="login.php">Login here</a></footer>

</form>

<?php view('footer') ?>