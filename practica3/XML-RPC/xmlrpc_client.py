# xmlrpc_client.py — Cliente XML-RPC
import xmlrpc.client

proxy = xmlrpc.client.ServerProxy("http://127.0.0.1:9000")
#res = proxy.primo(3)
#res = proxy.primo(56)
res = proxy.primo('1 or 1 --')
print("Resultado:", res)  # 35.5
