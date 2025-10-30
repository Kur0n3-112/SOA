/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package httpserver;

import com.sun.net.httpserver.HttpExchange;
import com.sun.net.httpserver.HttpHandler;
import static httpserver.HTTPServer.parseQuery;
import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.util.HashMap;
import java.util.Map;

/**
 *
 * @author Chuchi
 */
class EchoPostCalcHandler implements HttpHandler {
    // Esta petición se tiene que hacer de la siguiente forma:
    //  curl -X POST http://localhost:9000/echoCalc -d "num1=1&num2=2"
    // Respuesta esperable: 
    //  Suma: 3.00
    //  Resta: -1.00
    //  Multiplicación: 2.00
    //  División: 0.50

    @Override
    public void handle(HttpExchange he) throws IOException {
        // parse request
        Map<String, Object> parameters = new HashMap<String, Object>();

        // Lee el cuerpo de la petición entero, no solo una línea
        InputStreamReader isr = new InputStreamReader(he.getRequestBody(), "utf-8");
        BufferedReader br = new BufferedReader(isr);
        StringBuilder requestBody = new StringBuilder();
        String line;
        while ((line = br.readLine()) != null) {
            requestBody.append(line);
        }
        // Parseo del cuerpo de la petición en parámetros
        String query = requestBody.toString();
        parseQuery(query, parameters);

        // Extrae de la petición los valores y los introduce en variables.
        double num1 = Double.parseDouble((String) parameters.get("num1"));
        double num2 = Double.parseDouble((String) parameters.get("num2"));

        // Realiza las operaciones aritméticas que se piden.
        double suma = num1 + num2;
        double resta = num1 - num2;
        double multiplicacion = num1 * num2;
        double division = (num2 != 0) ? num1 / num2 : Double.NaN; // También comprueba que no se divida por 0.

        // Establece el formato de la respuesta con los resultados.
        String response = String.format(
            "Suma: %.2f\nResta: %.2f\nMultiplicación: %.2f\nDivisión: %.2f",
            suma, resta, multiplicacion, division
        );

        // Envía la respuesta.
        he.sendResponseHeaders(200, response.getBytes("utf-8").length);
        OutputStream os = he.getResponseBody();
        os.write(response.getBytes("utf-8"));
        os.close();
    }

    // Método auxiliar para parsear el query string en pares clave-valor
    private void parseQuery(String query, Map<String, Object> parameters) {
        if (query != null && !query.isEmpty()) {
            String[] pairs = query.split("&");
            for (String pair : pairs) {
                String[] keyValue = pair.split("=");
                if (keyValue.length > 1) {
                    parameters.put(keyValue[0], keyValue[1]);
                }
            }
        }
    }
}