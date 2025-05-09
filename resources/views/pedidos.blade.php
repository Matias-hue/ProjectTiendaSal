@extends('layouts.app')

@section('content')
    @include('layouts.header')
    <div class="py-4 px-8 text-sm text-gray-500">Pedidos</div>
    <main class="px-8">
        <h2 class="text-3xl font-bold mb-6">Gestión de Pedidos</h2>
        <div class="bg-white p-6 rounded-lg shadow-md">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif
            @if ($pedidos->isEmpty())
                <p class="text-gray-500">No hay pedidos disponibles.</p>
            @else
                <table class="w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border border-gray-300 p-2">ID</th>
                            <th class="border border-gray-300 p-2">Usuario</th>
                            <th class="border border-gray-300 p-2">Total</th>
                            <th class="border border-gray-300 p-2">Estado</th>
                            <th class="border border-gray-300 p-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pedidos as $pedido)
                            <tr>
                                <td class="border border-gray-300 p-2">{{ $pedido->id }}</td>
                                <td class="border border-gray-300 p-2">{{ $pedido->user->name }}</td>
                                <td class="border border-gray-300 p-2">${{ number_format($pedido->total, 2) }}</td>
                                <td class="border border-gray-300 p-2">{{ ucfirst($pedido->status) }}</td>
                                <td class="border border-gray-300 p-2">
                                    @if ($pedido->status === 'pending')
                                        <form action="{{ route('pedidos.complete', $pedido->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-blue-500 hover:text-blue-700">Marcar como Completado</button>
                                        </form>
                                    @endif
                                    <form action="{{ route('pedidos.destroy', $pedido->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </main>
    @include('layouts.footer')
@endsection