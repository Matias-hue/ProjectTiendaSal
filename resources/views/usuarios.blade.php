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
                <form method="GET" action="{{ route('usuarios.index') }}" class="input-search mb-4">
                    <input type="text" id="search" name="search" placeholder="Buscar por nombre o email..." value="{{ request('search') }}">
                    <button type="submit" class="btn-agregar" aria-label="Buscar usuarios">Buscar</button>
                </form>
                @if ($usuarios->isEmpty())
                    <p class="text-gray-500">No hay usuarios disponibles.</p>
                @else
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
                                        <a href="{{ route('usuarios.edit', $usuario->id) }}" class="btn-editar" aria-label="Editar usuario {{ $usuario->name }}">Editar</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="pagination mt-6">
                        {{ $usuarios->links() }}
                    </div>
                @endif
            </div>
        </div>
        <script src="{{ asset('js/usuarios.js') }}"></script>
    </main>
    @include('layouts.footer')
@endsection