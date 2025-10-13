# soap_server.py — Servidor SOAP (Spyne)
from spyne import Application, rpc, ServiceBase, Double
from spyne.protocol.soap import Soap11
from spyne.server.wsgi import WsgiApplication
from werkzeug.serving import run_simple

class CalcService(ServiceBase):
        @rpc(Double, Double, _returns=Double)
        def sum(ctx, a, b):
            return a + b
        @rpc(Double, Double, _returns=Double)
        def res(ctx, a, b):
            return a - b
        @rpc(Double, Double, _returns=Double)
        def div(ctx, a, b):
            return a / b
        @rpc(Double, Double, _returns=Double)
        def mul(ctx, a, b):
            return a * b

app = Application([CalcService],
                  tns='http://example.com/calc',
                  in_protocol=Soap11(validator='lxml'),
                  out_protocol=Soap11())

wsgi_app = WsgiApplication(app)

if __name__ == '__main__':
    print("SOAP Python server en http://127.0.0.1:8001/?wsdl")
    run_simple('127.0.0.1', 8001, wsgi_app)
