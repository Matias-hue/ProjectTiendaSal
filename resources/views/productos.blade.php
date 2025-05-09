@extends('layouts.app')

@section('content')
    @include('layouts.header') 
    <div class="py-4 px-8 text-sm text-gray-500">Productos</div>
    <main class="px-8">
        <h2 class="text-3xl font-bold mb-6">Nuestros Productos</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse ($productos as $producto)
                <div class="producto-card bg-white border rounded-lg shadow-md p-4">
                    <img src="{{ asset($producto->imagen ?? 'img/placeholder.jpg') }}" alt="{{ $producto->nombre }}" class="producto-imagen w-full h-48 object-cover rounded">
                    <h3 class="producto-nombre text-lg font-semibold mt-2">{{ $producto->nombre }}</h3>
                    <p class="producto-precio text-gray-600">${{ number_format($producto->precio, 2) }}</p>
                    <p class="producto-precio text-gray-600">Tamaño: {{ $producto->tamaño }}</p>
                    <p class="producto-stock text-sm text-gray-500">Stock: {{ $producto->stock }}</p>
                    @if (auth()->check())
                        <form action="{{ route('cart.add') }}" method="POST" class="mt-4">
                            @csrf
                            <input type="hidden" name="id" value="{{ $producto->id }}">
                            <input type="hidden" name="nombre" value="{{ $producto->nombre }}">
                            <input type="hidden" name="precio" value="{{ $producto->precio }}">
                            <div class="flex items-center space-x-2">
                                <input type="number" name="cantidad" value="1" min="1" max="{{ $producto->stock }}" class="border rounded px-2 py-1 w-16">
                                <button type="submit" class="btn-agregar-carrito bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                    Agregar al carrito
                                </button>
                            </div>
                        </form>
                    @else
                        <p class="text-sm text-red-500 mt-2">Inicia sesión para agregar al carrito.</p>
                    @endif
                </div>
            @empty
                <p class="text-gray-500">No hay productos disponibles.</p>
            @endforelse
        </div>
    </main>
    @include('layouts.footer')
@endsection