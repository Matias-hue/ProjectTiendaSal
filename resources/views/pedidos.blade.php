@extends('layouts.app')

@section('content')
    @include('layouts.header')
    <div class="py-4 px-8 text-sm text-gray-500">Pedidos</div>
    <main class="px-8">
        <h2 class="text-3xl font-bold mb-6">Gestión de Pedidos</h2>
        <div class="pedidos-container">
            @if ($pedidos->isEmpty())
                <p class="text-gray-500">No hay pedidos disponibles.</p>
            @else
                <table class="pedidos-table">
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
                                <td class="border border-gray-300 p-2 status-cell">
                                    {{ ucfirst(strtolower($pedido->status)) }}
                                </td>
                                <td class="border border-gray-300 p-2">
                                    @if ($pedido->status === 'Pendiente')
                                        <button class="btn-completar" data-id="{{ $pedido->id }}">Marcar como Completado</button>
                                    @endif
                                    @if ($pedido->status === 'Pendiente')
                                        <button class="btn-cancelar" data-id="{{ $pedido->id }}">Cancelar</button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <dialog id="dialog-completar">
            <h2 class="header-modal">Confirmar Completar</h2>
            <p id="mensaje-completar">¿Estás seguro de que deseas marcar este pedido como completado?</p>
            <div class="modal-botones-div">
                <button id="btn-confirmar-completar" class="modal-botones">Sí</button>
                <button id="btn-cerrar-completar" class="modal-botones">No</button>
            </div>
        </dialog>

        <dialog id="dialog-cancelar">
            <h2 class="header-modal">Confirmar Cancelar</h2>
            <p id="mensaje-cancelar">¿Estás seguro de que deseas cancelar este pedido?</p>
            <div class="modal-botones-div">
                <button id="btn-confirmar-cancelar" class="modal-botones">Sí</button>
                <button id="btn-cerrar-cancelar" class="modal-botones">No</button>
            </div>
        </dialog>

        <dialog id="dialog-error">
            <h2 class="header-modal">Error</h2>
            <p id="mensaje-error"></p>
            <div class="modal-botones-div">
                <button id="btn-cerrar-error" class="modal-botones">Cerrar</button>
            </div>
        </dialog>
    </main>
    @include('layouts.footer')
    <script src="{{ asset('js/pedidos.js') }}"></script>
@endsection