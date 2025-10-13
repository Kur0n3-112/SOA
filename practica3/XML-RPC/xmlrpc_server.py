# xmlrpc_server.py — Servidor XML-RPC
from xmlrpc.server import SimpleXMLRPCServer

def es_primo(n):
    if n <= 1:
        return False
    if n == 2:
        return True
    if n % 2 == 0:
        return False
    for i in range(3, int(n**0.5)+1, 2):
        if n % i == 0:
            return False
    return True

def divisor(n):
    if es_primo(n):
        return 1
    if n % 2 == 0:
        return 2
    for i in range(3, n+1, 2):
        if n % i == 0 and es_primo(i):
            return i
    return n

def comprobar_si_un_numero_es_un_numero(n):
    return isinstance(n, (int, float)) and not isinstance(n, bool)

def mostrar_n(n):
    return n

def primo(n):
    if comprobar_si_un_numero_es_un_numero(n) == True:
        return {
            "numero": mostrar_n(n),
            "es_primo": es_primo(n),
            "divisor": divisor(n)
        }
    else:
        return "No has introducido un numero, solo se permiten numeros."

if __name__ == "__main__":
    with SimpleXMLRPCServer(("0.0.0.0", 9000), allow_none=True) as server:
        server.register_function(primo, "primo")
        print("XML-RPC Python server en http://0.0.0.0:9000")
        server.serve_forever()
