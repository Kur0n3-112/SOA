# soap_client.py — Cliente SOAP (zeep)
from zeep import Client
import sys

def main():
    if len(sys.argv) < 3:
        print("Error: Este programa al menos requiere de que se le pase un parámetro..\n")
        print("USO: ",sys.argv[0]," <operacion> <valor 1> <valor 2>\n<operacion>: suma(sum), resta(res), multiplicación(mul) y división(div).\n<valor 1>, <valor2>: Números enteros o decimales para realizar las operaciones.")
        sys.exit(1)

    wsdl = "http://127.0.0.1:8001/?wsdl"
    client = Client(wsdl=wsdl)

    if sys.argv[1] == "sum":
        if len(sys.argv) < 4:
            print("Error:  Falta parametros para este tipo de operación 'sum'.\n")
            print("USO: ",sys.argv[0]," <operacion> <valor 1> <valor 2>\n<operacion>: suma(sum), resta(res), multiplicación(mul) y división(div).\n<valor 1>, <valor2>: Números enteros o decimales para realizar las operacioes.")
            sys.exit(1)

        res = client.service.sum(sys.argv[2], sys.argv[3])
        print("Resultado:", res)

    elif sys.argv[1] == "res":
        if len(sys.argv) < 4:
            print("Error:  Falta parametros para este tipo de operación 'res'.\n")
            print("USO: ",sys.argv[0]," <operacion> <valor 1> <valor 2>\n<operacion>: suma(sum), resta(res), multiplicación(mul) y división(div).\n<valor 1>, <valor2>: Números enteros o decimales para realizar las operacioes.")
            sys.exit(1)

        res = client.service.res(sys.argv[2], sys.argv[3])
        print("Resultado:", res)

    elif sys.argv[1] == "div":
        if len(sys.argv) < 4:
            print("Error:  Falta parametros para este tipo de operación 'div'.\n")
            print("USO: ",sys.argv[0]," <operacion> <valor 1> <valor 2>\n<operacion>: suma(sum), resta(res), multiplicación(mul) y división(div).\n<valor 1>, <valor2>: Números enteros o decimales para realizar las operacioes.")
            sys.exit(1)

        res = client.service.div(sys.argv[2], sys.argv[3])
        print("Resultado:", res)

    elif sys.argv[1] == "mul":
        if len(sys.argv) < 4:
            print("Error:  Falta parametros para este tipo de operación 'mul'.\n")
            print("USO: ",sys.argv[0]," <operacion> <valor 1> <valor 2>\n<operacion>: suma(sum), resta(res), multiplicación(mul) y división(div).\n<valor 1>, <valor2>: Números enteros o decimales para realizar las operacioes.")
            sys.exit(1)

        res = client.service.mul(sys.argv[2], sys.argv[3])
        print("Resultado:", res)

    else:
        print("Error: Tipo de operación invalida.\n")
        print("USO: ",sys.argv[0]," <operacion> <valor 1> <valor 2>\n<operacion>: suma(sum), resta(res), multiplicación(mul) y división(div).\n<valor 1>, <valor2>: Números enteros o decimales para realizar las operaciones.")
        sys.exit(1)

if __name__ == "__main__":
    main()
