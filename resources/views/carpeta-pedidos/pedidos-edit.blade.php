@extends('layouts.app')

@section('content')
    @include('layouts.header')
    <div class="py-4 px-8 text-sm text-gray-500">Editar Pedido</div>
    <main class="px-8">
        <div class="button-container">
            <h2 class="text-3xl font-bold mb-6">Editar Pedido #{{ $pedido->id }}</h2>
            <a href="{{ route('pedidos.index') }}" class="btn-volver-pedidos"> < </a>
        </div>
        <div class="container-registro">
            <div class="card-body-registro">
                @if (session('success'))
                    <div class="alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert-error">{{ session('error') }}</div>
                @endif
                <form id="edit-order-form" method="POST" action="{{ route('pedidos.update', $pedido->id) }}">
                    @csrf
                    @method('PUT')
                    <h3>Datos del Cliente</h3>
                    <p><strong>Nombre:</strong> {{ $pedido->user->name }}</p>
                    <p><strong>Email:</strong> {{ $pedido->user->email }}</p>
                    <h3>Productos</h3>
                    <table class="pedidos-table" id="items-table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pedido->items as $item)
                                <tr>
                                    <td>
                                        <select name="items[{{ $loop->index }}][product_id]" class="item-product">
                                            @foreach ($productos as $producto)
                                                <option value="{{ $producto->id }}" data-stock="{{ $producto->stock }}" {{ $producto->id == $item->product_id ? 'selected' : '' }}>
                                                    {{ $producto->nombre }} ({{ $producto->tamaño }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="items[{{ $loop->index }}][quantity]" value="{{ $item->quantity }}" min="1" class="item-quantity">
                                    </td>
                                    <td>
                                        <button type="button" class="btn-remove-item">Eliminar</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <button type="button" id="add-item" class="btn-agregar">Agregar Producto</button>
                    <button type="submit" class="btn-agregar">Guardar Cambios</button>
                </form>
            </div>
        </div>
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