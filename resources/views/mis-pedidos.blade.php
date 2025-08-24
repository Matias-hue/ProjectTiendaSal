@extends('layouts.app')

@section('content')
    @include('layouts.header')
    <div class="page-container">
        <div class="mis-pedidos-container">
            <h2 class="mis-pedidos-title">Mis Pedidos</h2>
            @if($pedidos->isEmpty())
                <p class="mis-pedidos-dialog-message">No tienes pedidos registrados.</p>
            @else
                <table class="mis-pedidos-table">
                    <thead class="mis-pedidos-table-header">
                        <tr>
                            <th>ID</th>
                            <th>Fecha</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pedidos as $pedido)
                            <tr>
                                <td class="mis-pedidos-table-cell">{{ $pedido->id }}</td>
                                <td class="mis-pedidos-table-cell">{{ $pedido->created_at->format('d/m/Y') }}</td>
                                <td class="mis-pedidos-table-cell">${{ number_format($pedido->total, 2) }}</td>
                                <td class="mis-pedidos-table-cell">
                                    <span class="mis-pedidos-status-badge status-{{ strtolower($pedido->status) }}">
                                        {{ $pedido->status }}
                                    </span>
                                </td>
                                <td class="mis-pedidos-action-cell">
                                    <a href="{{ route('pedidos.show', $pedido->id) }}" class="mis-pedidos-btn-detalles">Ver Detalles</a>
                                    <a href="{{ route('pedidos.pdf', $pedido->id) }}" class="mis-pedidos-btn-pdf">Descargar PDF</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mis-pedidos-pagination">
                    {{ $pedidos->links() }}
                </div>
            @endif
            <a href="{{ route('index') }}" class="mis-pedidos-btn-volver">Volver al Inicio</a>
        </div>
    </div>
    @include('layouts.footer')
@endsection