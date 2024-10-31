from fastapi import FastAPI

app = FastAPI()

@app.get("/")
async def root():
    return {"message": "Welcome to the FastAPI service"}

@app.get("/hello")
async def say_hello(name: str = "world"):
    return {"message": f"Hello, {name}!"}
