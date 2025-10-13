# SOAP / Python — Servidor y Cliente (Suma)

## Requisitos
```bash
python3 -m venv .venv && source .venv/bin/activate  # (opcional)
pip install -r requirements.txt
```

## Arrancar servidor
```bash
python3 soap_server.py
# WSDL: http://127.0.0.1:8001/?wsdl
```

## Probar cliente
```bash
python3 soap_client.py
# Resultado: 17.5
```
