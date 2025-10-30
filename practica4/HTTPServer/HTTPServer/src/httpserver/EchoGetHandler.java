/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package httpserver;

import com.sun.net.httpserver.HttpExchange;
import com.sun.net.httpserver.HttpHandler;
import static httpserver.HTTPServer.parseQuery;
import java.io.IOException;
import java.io.OutputStream;
import java.net.URI;
import java.util.HashMap;
import java.util.Map;

/**
 *
 * @author Chuchi
 */
class EchoGetHandler implements HttpHandler {

         @Override

         public void handle(HttpExchange he) throws IOException {
                 // parse request
                 Map<String, Object> parameters = new HashMap<String, Object>();
                 URI requestedUri = he.getRequestURI();
                 String query = requestedUri.getRawQuery();
                 parseQuery(query, parameters);

                 // send response
                 String response = "";
                 String respuesta="La respuesta es: 98";
                 for (String key : parameters.keySet())
                          response += key + " = " + parameters.get(key) + "n";
                 he.sendResponseHeaders(200, response.length());
                 OutputStream os = he.getResponseBody();
                 //os.write(response.toString().getBytes());
                 os.write(respuesta.toString().getBytes());
                 os.close();

         }

    
}
