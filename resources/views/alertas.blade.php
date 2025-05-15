@extends('layouts.app')

@section('content')
    @include('layouts.header')
    <main>
        <div class="py-4 px-8 text-sm text-gray-500">Alertas</div>
        <div class="container-pedidos px-8">
        <h2 class="text-3xl font-bold mb-6">Alertas del Sistema</h2>
            <div class="card-body-peidos">
                <h3 class="text-xl font-semibold mb-4">Pedidos Pendientes</h3>
                @if ($pedidosPendientes->isEmpty())
                    <p class="text-gray-500">No hay pedidos pendientes.</p>
                @else
                    <table class="w-full border-collapse border border-gray-300 mb-8">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border border-gray-300 p-2">ID Pedido</th>
                                <th class="border border-gray-300 p-2">Usuario</th>
                                <th class="border border-gray-300 p-2">Total</th>
                                <th class="border border-gray-300 p-2">Estado</th>
                            </tr>                            
                        </thead>
                        <tbody>
                            @foreach ($pedidosPendientes as $pedido)
                                <tr>
                                    <td>{{ $pedido->id }}</td>
                                    <td>{{ $pedido->user->name }}</td>
                                    <td>${{ number_format($pedido->total, 2)}}</td>
                                    <td>{{ ucfirst(strtolower($pedido->status)) }}</td>
                                </tr>
                            @endforeach   
                        </tbody>
                    </table>
                @endif

                <h3 class="text-xl font-semibold mb-4">Productos con Bajo Stock</h3>
                @if ($productosBajoStock->isEmpty())
                    <p class="text-gray-500">No hay productos con bajo stock.</p>
                @else
                    <table class="w-full border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border border-gray-300 p-2">ID Producto</th>
                                <th class="border border-gray-300 p-2">Nombre</th>
                                <th class="border border-gray-300 p-2">Tamaño</th>
                                <th class="border border-gray-300 p-2">Stock</th>
                            </tr>
                        </thead>   
                        <tbody>
                            @foreach ($productosBajoStock as $producto)
                                <tr>
                                    <td class="border border-gray-300 p-2">{{ $producto->id }}</td>
                                    <td class="border border-gray-300 p-2">{{ $producto->nombre}}</td>
                                    <td class="border border-gray-300 p-2">{{ $producto->tamaño }}</td>
                                    <td class="border border-gray-300 p-2">{{ $producto->stock }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </main>
    @include('layouts.footer')
@endsection