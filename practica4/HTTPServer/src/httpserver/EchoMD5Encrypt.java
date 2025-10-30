package httpserver;

import com.sun.net.httpserver.HttpExchange;
import com.sun.net.httpserver.HttpHandler;
import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;
import java.util.HashMap;
import java.util.Map;

/**
 *
 * @author Juan
 */
class EchoPostMD5Handler implements HttpHandler {
    //Se envia de la siguiente forma: curl -X POST http://localhost:9000/echoMD5 -d "word=hello"
    //Repuesta esperable:
    // Palabra encriptada (MD5): 5d41402abc4b2a76b9719d911017c592
    // Comprobación con desencriptador online: 5d41402abc4b2a76b9719d911017c592 : hello

    @Override
    public void handle(HttpExchange he) throws IOException {
        // parse request
        Map<String, Object> parameters = new HashMap<String, Object>();

        // Lee el cuerpo de la petición entero
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

        // Extrae el valor de la palabra
        String word = (String) parameters.get("word");

        // Realiza el encriptado en MD5
        String encryptedWord = encryptToMD5(word);

        // Establece el formato de la respuesta
        String response = "Palabra encriptada (MD5): " + encryptedWord;

        // Envía la respuesta
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

    // Método para encriptar en MD5
    private String encryptToMD5(String word) {
        try {
            MessageDigest md = MessageDigest.getInstance("MD5");
            byte[] messageDigest = md.digest(word.getBytes());
            StringBuilder hexString = new StringBuilder();
            for (byte b : messageDigest) {
                hexString.append(String.format("%02x", b));
            }
            return hexString.toString();
        } catch (NoSuchAlgorithmException e) {
            e.printStackTrace();
            return null;
        }
    }
}