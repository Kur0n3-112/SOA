-- Crea la base de datos y la tabla para XAMPP (utf8mb4 recomendado)

CREATE DATABASE IF NOT EXISTS registro_personas
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE registro_personas;

CREATE TABLE IF NOT EXISTS personas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(150) NOT NULL,
    dni VARCHAR(20) NOT NULL UNIQUE,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    contraseña VARCHAR(255) NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_usuario (usuario),
    INDEX idx_dni (dni)
);