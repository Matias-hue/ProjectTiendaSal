@extends('layouts.app')

@section('content')
    @include('layouts.header')
    <div class="py-4 px-8 text-sm text-gray-500">Editar Usuario</div>
    <main class="px-8">
        <h2 class="text-3xl font-bold mb-6">Editar Usuario #{{ $usuario->id }}</h2>
        <div class="container-registro">
            <div class="card-body-registro">
                @if (session('success'))
                    <div class="alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert-error">{{ session('error') }}</div>
                @endif
                <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="input-search-registro">
                        <label for="name">Nombre</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $usuario->name) }}" required>
                    </div>
                    <div class="input-search-registro">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $usuario->email) }}" required>
                    </div>
                    <div class="input-search-registro">
                        <label for="phone">Teléfono (incluye código de área)</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $usuario->phone) }}">
                    </div>
                    <div class="input-search-registro">
                        <label for="address">Dirección</label>
                        <input type="text" name="address" id="address" value="{{ old('address', $usuario->address) }}">
                    </div>
                    <div class="input-search-registro">
                        <label for="role">Rol</label>
                        <select name="role" id="role" required>
                            <option value="admin" {{ $usuario->role === 'admin' ? 'selected' : '' }}>Administrador</option>
                            <option value="user" {{ $usuario->role === 'user' ? 'selected' : '' }}>Usuario</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-agregar">Guardar Cambios</button>
                </form>
            </div>
        </div>
    </main>
    @include('layouts.footer')
    <script src="{{ asset('js/usuarios.js') }}"></script>
@endsection