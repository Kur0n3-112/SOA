package com.example.cliente;

import com.google.gson.Gson;
import com.google.gson.JsonObject;

import java.io.*;
import java.net.URI;
import java.net.http.HttpClient;
import java.net.http.HttpRequest;
import java.net.http.HttpResponse;
import java.nio.charset.StandardCharsets;
import java.nio.file.Files;
import java.nio.file.Path;
import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;

public class ClienteRegistro {
    // URLs adaptadas a XAMPP (PHP en Apache) y Logger Java en 8002
    private static final String PHP_SERVER_URL = "http://localhost/registro/registrar.php";
    private static final String JAVA_LOGGER_URL = "http://localhost:8002/registroLog";

    private static final String INPUT_FILE = "personas.txt";
    private static final String LOG_FILE = "cliente.log";
    private static final DateTimeFormatter formatter =
            DateTimeFormatter.ofPattern("yyyy-MM-dd HH:mm:ss");
    private static final Gson gson = new Gson();
    private static final HttpClient http = HttpClient.newHttpClient();

    public static void main(String[] args) {
        System.out.println("=== CLIENTE DE REGISTRO DE PERSONAS (Java) ===");
        Path inputPath = Path.of(INPUT_FILE);
        if (!Files.exists(inputPath)) {
            System.err.println("No se encontró el fichero " + INPUT_FILE + " en: " + inputPath.toAbsolutePath());
            System.exit(1);
        }

        try (BufferedReader br = Files.newBufferedReader(inputPath, StandardCharsets.UTF_8)) {
            String linea;
            int contador = 0;
            while ((linea = br.readLine()) != null) {
                linea = linea.trim();
                if (linea.isEmpty()) continue;

                String[] datos = linea.split("\\|");
                if (datos.length < 5) {
                    System.out.println("Línea inválida (se esperan 5 campos): " + linea);
                    continue;
                }

                String nombre = datos[0].trim();
                String apellidos = datos[1].trim();
                String dni = datos[2].trim().toUpperCase();
                String usuario = datos[3].trim();
                String contrasena = datos[4].trim();

                JsonObject persona = new JsonObject();
                persona.addProperty("Nombre", nombre);
                persona.addProperty("Apellidos", apellidos);
                persona.addProperty("DNI", dni);
                persona.addProperty("Usuario", usuario);
                persona.addProperty("Contraseña", contrasena);

                String resultado = enviarAlServidorPHP(persona);
                enviarLogServidorJava(usuario, dni, resultado);
                registrarLogLocal(usuario, resultado);

                System.out.println("Usuario " + usuario + ": " + resultado);
                contador++;
            }

            System.out.println("Procesamiento finalizado. Total líneas procesadas: " + contador);
        } catch (IOException e) {
            System.err.println("Error leyendo el fichero: " + e.getMessage());
        }
    }

    private static String enviarAlServidorPHP(JsonObject persona) {
        try {
            String json = gson.toJson(persona);
            HttpRequest req = HttpRequest.newBuilder()
                    .uri(URI.create(PHP_SERVER_URL))
                    .header("Content-Type", "application/json; charset=utf-8")
                    .POST(HttpRequest.BodyPublishers.ofString(json, StandardCharsets.UTF_8))
                    .build();

            HttpResponse<String> resp = http.send(req, HttpResponse.BodyHandlers.ofString(StandardCharsets.UTF_8));
            if (resp.statusCode() == 200) {
                String body = resp.body() != null ? resp.body().trim() : "";
                if ("OK".equalsIgnoreCase(body)) return "OK";
                if ("KO".equalsIgnoreCase(body)) return "KO";
                return body.isEmpty() ? "KO" : body;
            } else {
                return "KO";
            }
        } catch (Exception e) {
            System.err.println("Error al conectar con servidor PHP: " + e.getMessage());
            return "KO";
        }
    }

    private static void enviarLogServidorJava(String usuario, String dni, String resultado) {
        try {
            JsonObject log = new JsonObject();
            log.addProperty("usuario", usuario);
            log.addProperty("dni", dni);
            log.addProperty("timestamp", LocalDateTime.now().format(formatter));
            log.addProperty("resultado", resultado);

            String json = gson.toJson(log);
            HttpRequest req = HttpRequest.newBuilder()
                    .uri(URI.create(JAVA_LOGGER_URL))
                    .header("Content-Type", "application/json; charset=utf-8")
                    .POST(HttpRequest.BodyPublishers.ofString(json, StandardCharsets.UTF_8))
                    .build();

            http.send(req, HttpResponse.BodyHandlers.discarding());
        } catch (Exception e) {
            System.err.println("Error enviando log al servidor Java: " + e.getMessage());
        }
    }

    private static void registrarLogLocal(String usuario, String resultado) {
        String linea = String.format("[%s] Usuario: %s | Resultado: %s",
                LocalDateTime.now().format(formatter), usuario, resultado);
        try (PrintWriter out = new PrintWriter(new BufferedWriter(new FileWriter(LOG_FILE, true)))) {
            out.println(linea);
        } catch (IOException e) {
            System.err.println("Error escribiendo log local: " + e.getMessage());
        }
    }
}