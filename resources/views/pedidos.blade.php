@extends('layouts.app')

@section('content')
    @include('layouts.header')
    <div class="section-header">Pedidos</div>
    <main class="main-container">
        <h2 class="title-large">Gestión de Pedidos</h2>
        <div class="button-container">
            <a href="{{ route('pedidos.create') }}" class="btn-crear-pedido" aria-label="Crear un nuevo pedido">Crear Pedido</a>
        </div>
        <div class="pedidos-container">
            @if ($pedidos->isEmpty())
                <p class="no-data">No hay pedidos disponibles.</p>
            @else
                <table class="pedidos-table">
                    <thead>
                        <tr class="table-header">
                            <th class="table-cell">ID</th>
                            <th class="table-cell">Usuario</th>
                            <th class="table-cell">Total</th>
                            <th class="table-cell">Estado</th>
                            <th class="table-cell">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pedidos as $pedido)
                            <tr>
                                <td class="table-cell">{{ $pedido->id }}</td>
                                <td class="table-cell">{{ $pedido->user->name }}</td>
                                <td class="table-cell">${{ number_format($pedido->total, 2) }}</td>
                                <td class="table-cell status-cell">
                                    {{ ucfirst(strtolower($pedido->status)) }}
                                </td>
                                <td class="table-cell action-cell">
                                    @if ($pedido->status === 'Pendiente')
                                        <button class="btn-completar" data-id="{{ $pedido->id }}" aria-label="Marcar pedido #{{ $pedido->id }} como completado">Marcar como Completado</button>
                                        <button class="btn-cancelar" data-id="{{ $pedido->id }}" aria-label="Cancelar pedido #{{ $pedido->id }}">Cancelar</button>
                                        <a href="{{ route('pedidos.edit', $pedido->id) }}" class="btn-editar" aria-label="Editar pedido #{{ $pedido->id }}">Editar</a>
                                    @endif
                                    <button class="btn-detalles" data-id="{{ $pedido->id }}" aria-label="Ver detalles del pedido #{{ $pedido->id }}">Ver Detalles</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="pagination">
                    {{ $pedidos->links() }}
                </div>
            @endif
        </div>

        <dialog id="dialog-completar" aria-labelledby="completar-title">
            <h2 id="completar-title" class="header-modal">Confirmar Completar</h2>
            <p id="mensaje-completar" class="dialog-message">¿Estás seguro de que deseas marcar este pedido como completado?</p>
            <div class="modal-botones-div">
                <button id="btn-confirmar-completar" class="modal-botones">Sí</button>
                <button id="btn-cerrar-completar" class="modal-botones">No</button>
            </div>
        </dialog>

        <dialog id="dialog-cancelar" aria-labelledby="cancelar-title">
            <h2 id="cancelar-title" class="header-modal">Confirmar Cancelar</h2>
            <p id="mensaje-cancelar" class="dialog-message">¿Estás seguro de que deseas cancelar este pedido?</p>
            <div class="modal-botones-div">
                <button id="btn-confirmar-cancelar" class="modal-botones">Sí</button>
                <button id="btn-cerrar-cancelar" class="modal-botones">No</button>
            </div>
        </dialog>

        <dialog id="dialog-error" aria-labelledby="error-title">
            <h2 id="error-title" class="header-modal">Error</h2>
            <p id="mensaje-error" class="dialog-message"></p>
            <div class="modal-botones-div">
                <button id="btn-cerrar-error" class="modal-botones">Cerrar</button>
            </div>
        </dialog>

        <dialog id="dialog-success" aria-labelledby="success-title">
            <h2 id="success-title" class="header-modal">Éxito</h2>
            <p id="mensaje-success" class="dialog-message"></p>
            <div class="modal-botones-div">
                <button id="btn-cerrar-success" class="modal-botones">Cerrar</button>
            </div>
        </dialog>

        <dialog id="detailsModal" aria-labelledby="details-title">
            <h2 id="details-title" class="header-modal">Detalles del Pedido</h2>
            <div id="detailsContent" class="dialog-message"></div>
            <div class="modal-botones-div">
                <button class="modal-botones" onclick="document.getElementById('detailsModal').close()">Cerrar</button>
                <a href="#" id="pdfLink" class="modal-botones">Descargar PDF</a>
            </div>
        </dialog>
    </main>
    @include('layouts.footer')
@endsection