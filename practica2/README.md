# Python (FastAPI) — SUM (REST)

## Requisitos
```bash
pip install fastapi uvicorn requests
```

## Ejecutar el servidor
```bash
python3 server.py
```

## Probar con curl
```bash
curl "http://localhost:8000/api/v1/sum?a=2.5&b=4.2"
# -> {"result":6.7}
```

## Ejecutar el cliente
```bash
python3 client.py
# -> {'result': 20.5}
```
