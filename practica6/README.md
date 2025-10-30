# Registro de Personas — XAMPP + NetBeans

Arquitectura:
- PHP (XAMPP Apache): endpoint http://localhost/registro/registrar.php Inserta en MySQL y devuelve OK/KO.
- Java Logger (NetBeans Maven): servicio HTTP en puerto 8002 que guarda logs en `peticiones.log`.
- Java Cliente (NetBeans Maven): lee `personas.txt`, envía a PHP y registra logs (local y al Logger).

Requisitos
- XAMPP (Apache + PHP + MySQL/MariaDB) activo
- NetBeans con JDK 11+ (recomendado 17)
- Maven (NetBeans ya lo integra)

Estructura del paquete
```
registro-personas-xampp-netbeans/
├── README.md
├── htdocs/
│   └── registrar/
│       ├── registrar.php
│       └── setup_database.sql
└ netbeans/
  ├── java-logger/
  │   ├── pom.xml
  │   └── src/main/java/com/example/logger/ServidorJava.java
  └── java-client/
      ├── pom.xml
      ├── personas.txt
      └── src/main/java/com/example/cliente/ClienteRegistro.java
```

Pasos de despliegue

1) XAMPP — Copiar PHP y crear la BD
- Copia la carpeta `htdocs` a htdocs:
  - Windows: C:\\xampp\\htdocs\\registro\\
- Debe quedar:
  - htdocs/registro/registrar.php
  - htdocs/registro/setup_database.sql
- Inicia Apache y MySQL en el panel de XAMPP.
- Crea la base de datos con phpMyAdmin:
  - Abre http://localhost/phpmyadmin
  - Importa el archivo `setup_database.sql` (crea BD `registro_personas` y tabla `personas`).
- Credenciales por defecto en `registrar.php`:
  - Usuario: `root`, contraseña: vacía (ajústalo si tu XAMPP tiene otra clave).

2) NetBeans — Abrir y ejecutar los proyectos Java
- En NetBeans: File > Open Project y selecciona:
  - `java-logger`
  - `java-client`
- Ejecuta primero el Logger:
  - Run en `java-logger` (crea `peticiones.log` y escucha en puerto 8002).
- Ejecuta el Cliente:
  - Revisa/edita `java-client/personas.txt`.
  - Run en `java-client` (lee el fichero, llama a PHP y manda logs al Logger).
- URLs usadas:
  - PHP: http://localhost/registro/registrar.php
  - Logger: http://localhost:8002/registroLog

3) Verificación
- Datos insertados:
  - En phpMyAdmin, tabla `registro_personas`.`personas`
- Logs:
  - Cliente: `java-client/cliente.log`
  - Servidor Logger: `java-logger/peticiones.log`

Pruebas rápidas (opcional, se puede probar en el CMD de windows)
- PHP:
  ```
  curl -s -X POST http://localhost/registro/registrar.php \
    -H "Content-Type: application/json" \
    -d "{\"Nombre\":\"Ana\",\"Apellidos\":\"López\",\"DNI\":\"12345678Z\",\"Usuario\":\"ana.lopez\",\"Contraseña\":\"Secreta1\"}"
  ```
- Logger:
  ```
  curl -s -X POST http://localhost:8002/registroLog \
    -H "Content-Type: application/json" \
    -d "{\"usuario\":\"ana.lopez\",\"dni\":\"12345678Z\",\"timestamp\":\"2025-10-30 12:00:00\",\"resultado\":\"OK\"}"
  ```

Problemas comunes
- KO en PHP:
  - DNI inválido (usa 8 dígitos + 1 letra, ej. 12345678A).
  - Usuario o DNI duplicados (campos UNIQUE).
  - Credenciales MySQL incorrectas o `mysqli` no habilitado.
- 404 en PHP:
  - Verifica ruta: http://localhost/registro/registrar.php y que el archivo esté en htdocs/registro/.
- Puerto 8002 ocupado:
  - Cambia `PUERTO` en `ServidorJava` y ajusta `JAVA_LOGGER_URL` en `ClienteRegistro`.

Configuración
- Si cambias credenciales de MySQL, edita en `php/registrar.php`:
  - `$dbuser`, `$dbpassword`
- Si cambias puertos o hosts, edita en `java-client/ClienteRegistro.java`:
  - `PHP_SERVER_URL`, `JAVA_LOGGER_URL`