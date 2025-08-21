@extends('layouts.app')

@section('content')
    @include('layouts.header')
    <div class="py-4 px-8 text-sm text-gray-500">Crear Pedido</div>
    <main class="px-8">
        <h2 class="text-3xl font-bold mb-6">Crear Nuevo Pedido</h2>
        <div class="container-registro">
            <div class="card-body-registro">
                @if (session('success'))
                    <div class="alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert-error">{{ session('error') }}</div>
                @endif
                <form id="create-order-form" method="POST" action="{{ route('pedidos.store') }}">
                    @csrf
                    <div class="input-search-registro">
                        <label for="user_id">Usuario</label>
                        <select name="user_id" id="user_id" required>
                            @foreach ($usuarios as $usuario)
                                <option value="{{ $usuario->id }}">{{ $usuario->name }} ({{ $usuario->email }})</option>
                            @endforeach
                        </select>
                    </div>
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
                            <tr>
                                <td>
                                    <select name="items[0][product_id]" class="item-product">
                                        @foreach ($productos as $producto)
                                            <option value="{{ $producto->id }}" data-stock="{{ $producto->stock }}">{{ $producto->nombre }} ({{ $producto->tamaño }})</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="items[0][quantity]" value="1" min="1" class="item-quantity">
                                </td>
                                <td>
                                    <button type="button" class="btn-remove-item">Eliminar</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <button type="button" id="add-item" class="btn-agregar">Agregar Producto</button>
                    <button type="submit" class="btn-agregar">Crear Pedido</button>
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