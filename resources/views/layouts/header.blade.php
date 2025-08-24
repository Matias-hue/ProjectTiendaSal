<header class="header">
    <div class="flex justify-between items-center py-2 px-8 bg-gray-100">
        <div class="flex-grow flex justify-end items-center space-x-4">
            @guest
                <a class="text-gray-700 hover:text-blue-900" href="{{ route('register') }}">Crear cuenta</a>
                <a class="text-gray-700 hover:text-blue-900" href="{{ route('login') }}">Iniciar sesión</a>
            @else 
                @auth
                    <a class="text-gray-700 hover:text-blue-900" href="{{ route('profile.edit') }}">Mi Perfil</a>
                    <a class="text-gray-700 hover:text-blue-900" href="{{ route('mis-pedidos') }}">Mis Pedidos</a>
                @endauth
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

        <nav class="hidden md:flex space-x-8 text-lg">
            <a class="text-gray-700 hover:text-blue-900" href="{{ route('index') }}">INICIO</a>
            <a class="text-gray-700 hover:text-blue-900" href="{{ route('productos') }}">PRODUCTOS</a>
            <a class="text-gray-700 hover:text-blue-900" href="{{ route('ubicacion') }}">UBICACIÓN</a>
        </nav>

        <div class="flex items-center space-x-4">
            @if(auth()->check())
                <a href="{{ route('cart') }}" class="cart relative">
                    <i class="fas fa-shopping-cart text-gray-700 text-2xl"></i>
                    <span class="badge">{{ count(session('carrito', [])) }}</span>
                </a>
            @endif
        </div>        
    </div>
</header>