#Alumno: Juan Riego Vila
#Asignatura: Arquitectura Orienta a Servicios (SOA)
#Grado: Ingenieria Informática en Sistemas de Información (GIISI).
#Fecha: 02-10-2025

# server.py — FastAPI
# Servidor REST con endpoint GET /api/v1/sum?a=&b= → {"result": a+b}
from fastapi import FastAPI, Query
import uvicorn

app = FastAPI(title="Calc REST (SUM)")

@app.get("/api/v1/sum")
def sum_endpoint(a: float = Query(..., description="Operando A"),
                 b: float = Query(..., description="Operando B")):
    # FastAPI valida y convierte los query params a float; si no puede, responde 422 automáticamente
    return {"result": a + b}

@app.get("/api/v1/res")
def res_endpoint(a: float = Query(..., description="Operando A"),
                 b: float = Query(..., description="Operando B")):
    # FastAPI valida y convierte los query params a float; si no puede, responde 422 automáticamente
    return {"result": a - b}

@app.get("/api/v1/div")
def div_endpoint(a: float = Query(..., description="Operando A"),
                 b: float = Query(..., description="Operando B")):
    # FastAPI valida y convierte los query params a float; si no puede, responde 422 automáticamente
    return {"result": a / b}

@app.get("/api/v1/mul")
def mul_endpoint(a: float = Query(..., description="Operando A"),
                 b: float = Query(..., description="Operando B")):
    # FastAPI valida y convierte los query params a float; si no puede, responde 422 automáticamente
    return {"result": a * b}

if __name__ == "__main__":
    # Arranca el servidor ASGI (puerto 8000)
    uvicorn.run("server:app", host="0.0.0.0", port=8000, reload=False)
