@extends('layouts.app')

@section('content')
    @include('layouts.header')
    <main>
        <div class="section-header">Usuarios</div>
        <div class="container-usuarios">
            <div class="card-body-usuarios">
                @if (session('success'))
                    <div class="alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert-error">{{ session('error') }}</div>
                @endif
                <form method="GET" action="{{ route('usuarios.index') }}" class="input-search">
                    <input type="text" id="search" name="search" placeholder="Buscar por nombre o email..." value="{{ request('search') }}">
                    <button type="submit" class="btn-buscar" aria-label="Buscar usuarios">Buscar</button>
                </form>
                @if ($usuarios->isEmpty())
                    <p class="no-data">No hay usuarios disponibles.</p>
                @else
                    <div class="table-container">
                        <table class="tabla-usuarios">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Usuario</th>
                                    <th>Correo</th>
                                    <th>Télefono</th>
                                    <th>Dirección</th>
                                    <th>Rol</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="usuarios-table-body">
                                @foreach($usuarios as $usuario)
                                    <tr>
                                        <td>{{ $usuario->id }}</td>
                                        <td>{{ $usuario->name ?? 'No disponible' }}</td>
                                        <td>{{ $usuario->email ?? 'No disponible' }}</td>
                                        <td>{{ $usuario->phone ?? 'No disponible' }}</td>
                                        <td>{{ $usuario->address ?? 'No disponible '}}</td>
                                        <td>{{ $usuario->role ?? 'Usuario' }}</td>
                                        <td>
                                            <a href="{{ route('usuarios.edit', $usuario->id) }}" class="btn-editar" aria-label="Editar usuario {{ $usuario->name }}">Editar</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination">
                        {{ $usuarios->links() }}
                    </div>
                @endif
            </div>
        </div>
    </main>
    @include('layouts.footer')
@endsection