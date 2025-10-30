<?php
// Servidor PHP: Inserta en MySQL y devuelve "OK" o "KO" en texto plano para el cliente Java.

header('Content-Type: text/plain');

// Configuración de la base de datos (ajusta si tu XAMPP tiene contraseña para root)
$servername = "localhost";
$dbuser = "root";
$dbpassword = "";
$database = "registro_personas";

try {
    // Conexión a MySQL/MariaDB
    $conn = new mysqli($servername, $dbuser, $dbpassword, $database);
    if ($conn->connect_error) {
        http_response_code(500);
        echo "KO";
        exit;
    }

    // Leer JSON
    $raw = file_get_contents("php://input");
    $data = json_decode($raw, true);

    if (!is_array($data)) {
        http_response_code(400);
        echo "KO";
        $conn->close();
        exit;
    }

    // Validar campos requeridos
    $required = ['Nombre', 'Apellidos', 'DNI', 'Usuario', 'Contraseña'];
    foreach ($required as $field) {
        if (!isset($data[$field]) || trim($data[$field]) === '') {
            http_response_code(400);
            echo "KO";
            $conn->close();
            exit;
        }
    }

    // Sanitizar y preparar datos
    $nombre = trim($data['Nombre']);
    $apellidos = trim($data['Apellidos']);
    $dni = strtoupper(trim($data['DNI']));
    $usuario = trim($data['Usuario']);
    $passwordHash = password_hash($data['Contraseña'], PASSWORD_BCRYPT);

    // Validación básica de DNI: 8 dígitos + 1 letra
    if (!preg_match('/^[0-9]{8}[A-Z]$/', $dni)) {
        echo "KO";
        $conn->close();
        exit;
    }

    // Comprobar duplicados (usuario o DNI)
    $stmtCheck = $conn->prepare("SELECT id FROM personas WHERE usuario = ? OR dni = ?");
    $stmtCheck->bind_param("ss", $usuario, $dni);
    $stmtCheck->execute();
    $stmtCheck->store_result();

    if ($stmtCheck->num_rows > 0) {
        echo "KO";
        $stmtCheck->close();
        $conn->close();
        exit;
    }
    $stmtCheck->close();

    // Insertar usando prepared statements
    $stmt = $conn->prepare("INSERT INTO personas (nombre, apellidos, dni, usuario, contraseña) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nombre, $apellidos, $dni, $usuario, $passwordHash);

    if ($stmt->execute()) {
        echo "OK";
    } else {
        echo "KO";
    }

    $stmt->close();
    $conn->close();

} catch (Throwable $e) {
    http_response_code(500);
    echo "KO";
}