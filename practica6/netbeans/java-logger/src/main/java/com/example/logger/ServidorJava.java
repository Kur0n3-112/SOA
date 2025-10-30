package com.example.logger;

import com.google.gson.Gson;
import com.google.gson.JsonObject;
import com.sun.net.httpserver.HttpServer;
import com.sun.net.httpserver.HttpExchange;

import java.io.*;
import java.net.InetSocketAddress;
import java.nio.charset.StandardCharsets;
import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;

public class ServidorJava {
    private static final int PUERTO = 8002;
    private static final String LOG_FILE = "peticiones.log";
    private static final DateTimeFormatter formatter =
            DateTimeFormatter.ofPattern("yyyy-MM-dd HH:mm:ss");
    private static final Gson gson = new Gson();

    public static void main(String[] args) throws IOException {
        HttpServer servidor = HttpServer.create(new InetSocketAddress(PUERTO), 0);
        servidor.createContext("/registroLog", ServidorJava::handleLog);
        servidor.setExecutor(null);
        servidor.start();
        System.out.println("Servidor Java Logger escuchando en puerto " + PUERTO);
        System.out.println("Log file: " + new File(LOG_FILE).getAbsolutePath());
    }

    private static void handleLog(HttpExchange exchange) throws IOException {
        try {
            if (!"POST".equalsIgnoreCase(exchange.getRequestMethod())) {
                exchange.sendResponseHeaders(405, -1);
                return;
            }

            String body = readBody(exchange.getRequestBody());
            JsonObject json = gson.fromJson(body, JsonObject.class);

            String usuario = getOrDefault(json, "usuario", "DESCONOCIDO");
            String dni = getOrDefault(json, "dni", "DESCONOCIDO");
            String timestampCliente = getOrDefault(json, "timestamp", "");
            String resultado = getOrDefault(json, "resultado", "DESCONOCIDO");

            registrarLog(usuario, dni, timestampCliente, resultado);

            byte[] ok = "{\"estado\":\"OK\"}".getBytes(StandardCharsets.UTF_8);
            exchange.getResponseHeaders().add("Content-Type", "application/json; charset=utf-8");
            exchange.sendResponseHeaders(200, ok.length);
            try (OutputStream os = exchange.getResponseBody()) {
                os.write(ok);
            }
        } catch (Exception e) {
            exchange.sendResponseHeaders(500, -1);
        } finally {
            exchange.close();
        }
    }

    private static String getOrDefault(JsonObject json, String key, String def) {
        return json != null && json.has(key) && !json.get(key).isJsonNull()
                ? json.get(key).getAsString()
                : def;
    }

    private static String readBody(InputStream is) throws IOException {
        ByteArrayOutputStream baos = new ByteArrayOutputStream();
        byte[] buf = new byte[2048];
        int r;
        while ((r = is.read(buf)) != -1) {
            baos.write(buf, 0, r);
        }
        return baos.toString(StandardCharsets.UTF_8);
    }

    private static void registrarLog(String usuario, String dni, String timestampCliente, String resultado) {
        String timestampServidor = LocalDateTime.now().format(formatter);
        String linea = String.format("[%s] Usuario: %s | DNI: %s | Timestamp Cliente: %s | Resultado: %s",
                timestampServidor, usuario, dni, timestampCliente, resultado);
        try (PrintWriter out = new PrintWriter(new BufferedWriter(new FileWriter(LOG_FILE, true)))) {
            out.println(linea);
            System.out.println("Log registrado: " + linea);
        } catch (IOException e) {
            System.err.println("Error escribiendo log: " + e.getMessage());
        }
    }
}