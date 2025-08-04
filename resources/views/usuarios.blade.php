@extends('layouts.app')

@section('content')
    @include('layouts.header')
    <main>
        <div class="py-4 px-8 text-sm text-gray-500">Usuarios</div>
        <div class="container-usuarios">
            <div class="card-body-usuarios">
                @if (session('success'))
                    <div class="alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert-error">{{ session('error') }}</div>
                @endif
                <div class="input-search">
                    <input type="text" id="search" placeholder="Buscar...">
                </div>
                <table class="tabla-usuarios">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Rol</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="usuarios-table-body">
                        @foreach($usuarios as $usuario)
                            <tr>
                                <td>{{ $usuario->id }}</td>
                                <td>{{ $usuario->name }}</td>
                                <td>{{ $usuario->role ?? 'Usuario' }}</td>
                                <td>
                                    <a href="{{ route('usuarios.edit', $usuario->id) }}" class="btn-editar">Editar</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <script src="{{ asset('js/usuarios.js') }}"></script>
    </main>
    @include('layouts.footer')
@endsection