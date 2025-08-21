@extends('layouts.app')

@section('content')
    @include('layouts.header')
    <div class="py-4 px-8 text-sm text-gray-500">Carrito de Compras</div>
    <main class="px-8">
        <h2 class="titulo-carrito text-3xl font-bold mb-6">Tu Carrito</h2>
        <div class="carrito-contenedor">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @if (!empty($carrito))
                <ul class="lista-productos">
                    @foreach ($carrito as $index => $item)
                        <li class="producto-item">
                            <div>
                                <span>{{ $item['nombre'] }}</span>
                                <span>(Cantidad: {{ $item['cantidad'] }})</span>
                            </div>
                            <div class="precio-item">${{ number_format($item['precio'] * $item['cantidad'], 2) }}</div>
                            <form action="{{ route('cart.remove', $index) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700">Eliminar</button>
                            </form>
                        </li>
                    @endforeach
                </ul>
                <div class="mt-4">
                    <p class="font-bold">Total: ${{ number_format(collect($carrito)->sum(fn($item) => $item['precio'] * $item['cantidad']), 2) }}</p>
                </div>
                <div class="botones-accion-carrito">
                    <form action="{{ route('checkout') }}" method="POST" class="mt-4">
                        @csrf
                        <button type="submit" class="btn-proceder">Proceder al Pago</button>
                    </form>
                        <a href="{{ route('productos') }}" class="btn-volver-carrito">Volver</a>
                </div>
                
            @else
                <p class="mensaje-vacio">Tu carrito está vacío.</p>
                <a href="{{ route('productos') }}" class="btn-volver-carrito">Volver</a>
            @endif
        </div>
    </main>
    @include('layouts.footer')
@endsection