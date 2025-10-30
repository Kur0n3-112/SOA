/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package httpserver;

import com.sun.net.httpserver.HttpServer;
import java.io.IOException;
import java.io.UnsupportedEncodingException;
import java.net.InetSocketAddress;
import java.net.URLDecoder;
import java.util.ArrayList;
import java.util.List;
import java.util.Map;


/**
 *
 * @author Chuchi
 */
public class HTTPServer {

    /**
     * @param args the command line arguments
     */
    public static void parseQuery(String query, Map<String, Object> parameters) throws UnsupportedEncodingException {

            if (query != null) {
                    String pairs[] = query.split("[&]");
                    for (String pair : pairs) {
                             String param[] = pair.split("[=]");
                             String key = null;
                             String value = null;
                             if (param.length > 0) {
                             key = URLDecoder.decode(param[0], System.getProperty("file.encoding"));
                             }


                             if (param.length > 1) {
                                      value = URLDecoder.decode(param[1], System.getProperty("file.encoding"));
                             }

                             if (parameters.containsKey(key)) {
                                      Object obj = parameters.get(key);
                                      if (obj instanceof List<?>) {
                                               List values = (List) obj;
                                               values.add(value);

                                      } else if (obj instanceof String) {
                                               List values = new ArrayList();
                                               values.add((String) obj);
                                               values.add(value);
                                               parameters.put(key, values);
                                      }
                             } else {
                                      parameters.put(key, value);
                             }

                    }

            }

   }
    
    public static void main(String[] args) throws IOException {

        int port = 9000;
        HttpServer server = HttpServer.create(new InetSocketAddress(port), 0);
        System.out.println("server started at " + port);
        server.createContext("/", new RootHandler());
        server.createContext("/echoHeader", new EchoHeadexrHandler());
        server.createContext("/echoGet", new EchoGetHandler());
        server.createContext("/echoPost", new EchoPostHandler());
        server.setExecutor(null);
        server.start();
    }

}
