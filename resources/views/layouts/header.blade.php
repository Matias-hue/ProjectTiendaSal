<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sal La Isabela</title>

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.js"></script>

    {{-- FontAwesome --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>

    {{-- Estilos propios con Laravel --}}
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}"/>
</head>

<body class="font-roboto">

<header class="header">
    <div class="flex justify-between items-center py-2 px-8 bg-gray-100">
        <div class="flex-grow flex justify-end items-center space-x-4">
            @guest
                <a class="text-gray-700 hover:text-blue-900" href="{{ route('register') }}">Crear cuenta</a>
                <a class="text-gray-700 hover:text-blue-900" href="{{ route('login') }}">Iniciar sesión</a>
            @else 
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-gray-700 hover:text-blue-900">Cerrar sesión</button>
                </form>
            @endguest
        </div>
    </div>

    <div class="nav flex justify-between items-center py-2 px-8">
        <div class="flex items-center">
            <img alt="Logo de Sal La Isabela" class="logo" src="{{ asset('img/LogoLaIsabela.jpg') }}"/>
            <span class="ml-4 text-2xl font-bold">Sal La Isabela</span>
        </div>

        {{-- Menú de navegación --}}
        <nav class="hidden md:flex space-x-8 text-lg">
            <a class="text-gray-700 hover:text-blue-900" href="{{ route('index') }}">INICIO</a>
            <a class="text-gray-700 hover:text-blue-900" href="{{ route('productos') }}">PRODUCTOS</a>
            <a class="text-gray-700 hover:text-blue-900" href="{{ route('contacto') }}">CONTACTO</a>
            <a class="text-gray-700 hover:text-blue-900" href="{{ route('ubicacion') }}">UBICACIÓN</a>
        </nav>

        
        <div class="flex items-center space-x-4">
            @if(auth()->check())
            <a href="{{ route('cart') }}" class="cart relative">
                <i class="fas fa-shopping-cart text-gray-700 text-2xl"></i>
                <span class="badge">1</span>
            </a>
            @endif
        </div>        
    </div>
</header>
