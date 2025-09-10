@if(auth()->check() && auth()->user()->role === 'admin')

@extends('layouts.app')
@section('content')
@include('layouts.header')
<body>
    <main>
        <div class="py-4 px-8 text-sm text-gray-500">Inventario</div>
        <div class="container-inventario">
            <div class="card-body-inventario">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Tamaño</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Editar</th>
                            <th>Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($productos as $producto)
                            <tr class="{{ $producto->stock <= 100 ? 'stock-bajo' : '' }}">
                                <td>{{ $producto->id }}</td>
                                <td>{{ $producto->nombre }}</td>
                                <td>{{ $producto->tamaño }}</td>
                                <td>{{ number_format($producto->precio, 2) }}</td>
                                <td>{{ $producto->stock }}</td>
                                <td>
                                    <button class="btn-editar-tabla" 
                                            data-id="{{ $producto->id }}" 
                                            data-nombre="{{ $producto->nombre }}" 
                                            data-tamaño="{{ $producto->tamaño }}" 
                                            data-precio="{{ $producto->precio }}" 
                                            data-stock="{{ $producto->stock }}">
                                        {{ __('Editar') }}
                                    </button>
                                </td>
                                <td>
                                    <button class="btn-eliminar-tabla" 
                                            data-id="{{ $producto->id }}"
                                            data-nombre="{{ $producto->nombre }}">
                                        {{ __('Eliminar') }}
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <button type="button" class="btn-agregar" id="btn-agregar">
                    {{ __('Agregar Producto') }}
                </button>
            </div>
        </div>

        <dialog id="dialog-agregar">
            <form method="POST" action="{{ route('productos.store') }}">
                @csrf
                <h2 class="header-modal">Agregar Producto</h2>
                <div class="modal-agregar">
                    <label for="nombre">{{ __('Nombre: ') }}</label>
                    <input type="text" id="nombre" name="nombre" class="modal-agregar-caja" required>
                </div>
                <div class="modal-agregar">
                    <label for="tamaño">{{ __('Tamaño: ') }}</label>
                    <input type="text" id="tamaño" name="tamaño" class="modal-agregar-caja" required>
                </div>
                <div class="modal-agregar">
                    <label for="precio">{{ __('Precio: ') }}</label>
                    <input type="number" id="precio" name="precio" class="modal-agregar-caja" required>
                </div>
                <div class="modal-agregar">
                    <label for="stock">{{ __('Stock: ') }}</label>
                    <input type="number" id="stock" name="stock" class="modal-agregar-caja">
                </div>
                <div class="modal-botones-div">
                    <button type="submit" class="modal-botones">{{ __('Guardar') }}</button>
                    <button type="button" id="btn-cerrar" class="modal-botones">{{ __('Cerrar') }}</button>
                </div>
            </form>
        </dialog>

        <dialog id="dialog-editar">
            <form method="POST" id="form-editar">
                @csrf
                @method('PUT')
                <h2 class="header-modal">Editar Producto</h2>
                <div class="modal-agregar">
                    <label for="nombre">{{ __('Nombre: ') }}</label>
                    <input type="text" id="edit-nombre" name="nombre" class="modal-agregar-caja" required>
                </div>
                <div class="modal-agregar">
                    <label for="tamaño">{{ __('Tamaño: ') }}</label>
                    <input type="text" id="edit-tamaño" name="tamaño" class="modal-agregar-caja" required>
                </div>
                <div class="modal-agregar">
                    <label for="precio">{{ __('Precio: ') }}</label>
                    <input type="number" id="edit-precio" name="precio" class="modal-agregar-caja" required>
                </div>
                <div class="modal-agregar">
                    <label for="stock">{{ __('Stock: ') }}</label>
                    <input type="number" id="edit-stock" name="stock" class="modal-agregar-caja">
                </div>
                <div class="modal-botones-div">
                    <button type="submit" class="modal-botones">{{ __('Guardar') }}</button>
                    <button type="button" id="btn-cerrar-editar" class="modal-botones">{{ __('Cerrar') }}</button>
                </div>
            </form>
        </dialog>

        <dialog id="dialog-eliminar">
            <h2 class="header-modal">Confirmar Eliminación</h2>
            <p id="mensaje-eliminar"></p>
            <div class="modal-botones-div">
                <button id="btn-confirmar-eliminar" class="modal-botones">{{ __('Sí') }}</button>
                <button id="btn-cerrar-eliminar" class="modal-botones">{{ __('No') }}</button>
            </div>
        </dialog>
    </main>
    @include('layouts.footer')
@endsection

@endif