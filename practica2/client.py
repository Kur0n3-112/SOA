#Alumno: Juan Riego Vila
#Asignatura: Arquitectura Orienta a Servicios (SOA)
#Grado: Ingenieria Informática en Sistemas de Información (GIISI).
#Fecha: 02-10-2025

# client.py — requests
# Cliente REST que invoca GET /api/v1/sum?a=&b= y muestra el JSON devuelto
import requests
import sys

def main():
    if len(sys.argv) < 2:
        print("Error: Este programa al menos requiere de que se le pase un parámetro..\n")
        print("USO: ",sys.argv[0]," <operacion> <valor 1> <valor 2>\n<operacion>: suma(sum), resta(res), multiplicación(mul) y división(div).\n<valor 1>, <valor2>: Números enteros o decimales para realizar las operaciones.")
        sys.exit(1)
    if sys.argv[1] == "sum":
        if len(sys.argv) < 3:
            print("Error:  Falta parametros para este tipo de operación 'sum'.\n")
            print("USO: ",sys.argv[0]," <operacion> <valor 1> <valor 2>\n<operacion>: suma(sum), resta(res), multiplicación(mul) y división(div).\n<valor 1>, <valor2>: Números enteros o decimales para realizar las operacioes.")
            sys.exit(1)
        url = "http://0.0.0.0:8000/api/v1/sum"
        params = {"a": sys.argv[2], "b": sys.argv[3]}
    elif sys.argv[1] == "res":
        if len(sys.argv) < 3:
            print("Error:  Falta parametros para este tipo de operación 'res'.\n")
            print("USO: ",sys.argv[0]," <operacion> <valor 1> <valor 2>\n<operacion>: suma(sum), resta(res), multiplicación(mul) y división(div).\n<valor 1>, <valor2>: Números enteros o decimales para realizar las operacioes.")
            sys.exit(1)
        url = "http://0.0.0.0:8000/api/v1/res"
        params = {"a": sys.argv[2], "b": sys.argv[3]}
    elif sys.argv[1] == "div":
        if len(sys.argv) < 3:
            print("Error:  Falta parametros para este tipo de operación 'div'.\n")
            print("USO: ",sys.argv[0]," <operacion> <valor 1> <valor 2>\n<operacion>: suma(sum), resta(res), multiplicación(mul) y división(div).\n<valor 1>, <valor2>: Números enteros o decimales para realizar las operacioes.")
            sys.exit(1)
        url = "http://0.0.0.0:8000/api/v1/div"
        params = {"a": sys.argv[2], "b": sys.argv[3]}
    elif sys.argv[1] == "mul":
        if len(sys.argv) < 3:
            print("Error:  Falta parametros para este tipo de operación 'mul'.\n")
            print("USO: ",sys.argv[0]," <operacion> <valor 1> <valor 2>\n<operacion>: suma(sum), resta(res), multiplicación(mul) y división(div).\n<valor 1>, <valor2>: Números enteros o decimales para realizar las operacioes.")
            sys.exit(1)
        url = "http://0.0.0.0:8000/api/v1/mul"
        params = {"a": sys.argv[2], "b": sys.argv[3]}
    else:
        print("Error: Tipo de operación invalida.\n")
        print("USO: ",sys.argv[0]," <operacion> <valor 1> <valor 2>\n<operacion>: suma(sum), resta(res), multiplicación(mul) y división(div).\n<valor 1>, <valor2>: Números enteros o decimales para realizar las operaciones.")
        sys.exit(1)

    r = requests.get(url, params=params, timeout=5)
    r.raise_for_status()      # lanza excepción si el servidor respondió con error HTTP
    print(r.json())           # {'result': 20.5}

if __name__ == "__main__":
    main()
