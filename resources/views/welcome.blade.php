<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inicio</title>
    @vite(['resources/sass/app.scss'])
</head>
<body>
    <div class="container">
        <h1>Bienvenido</h1>
        <a href="{{ route('login') }}">Iniciar sesión</a> | 
        <a href="{{ route('register') }}">Registrarse</a>
    </div>
</body>
</html>