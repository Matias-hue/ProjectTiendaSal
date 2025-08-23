@if(auth()->check() && auth()->user()->role === 'admin')

@extends('layouts.app')
@section('content')
    @include('layouts.header')
    <div class="section-header">Crear Usuario</div>
    <main class="main-container">
        <div class="button-container">
            <h2 class="title-large">Nuevo Usuario</h2>
            <a href="{{ route('usuarios.index') }}" class="btn-volver-usuarios" aria-label="Volver a la lista de usuarios"> < </a>
        </div>
        <div class="container-registro">
            <div class="card-body-registro">
                @if (session('success'))
                    <div class="alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert-error">{{ session('error') }}</div>
                @endif
                <form action="{{ route('usuarios.store') }}" method="POST" class="create-user-form">
                    @csrf
                    <div class="input-search-registro">
                        <label for="name">Nombre</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required>
                        @error('name')
                            <span class="validation-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="input-search-registro">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required>
                        @error('email')
                            <span class="validation-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="input-search-registro">
                        <label for="password">Contraseña</label>
                        <input type="password" name="password" id="password" required>
                        @error('password')
                            <span class="validation-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="input-search-registro">
                        <label for="password_confirmation">Confirmar Contraseña</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required>
                        @error('password_confirmation')
                            <span class="validation-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="input-search-registro">
                        <label for="phone">Teléfono (incluye código de área)</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}">
                        @error('phone')
                            <span class="validation-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="input-search-registro">
                        <label for="address">Dirección</label>
                        <input type="text" name="address" id="address" value="{{ old('address') }}">
                        @error('address')
                            <span class="validation-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="input-search-registro">
                        <label for="role">Rol</label>
                        <select name="role" id="role" required>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrador</option>
                            <option value="user" {{ old('role', 'user') === 'user' ? 'selected' : '' }}>Usuario</option>
                        </select>
                        @error('role')
                            <span class="validation-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" class="btn-agregar">Crear Usuario</button>
                </form>
            </div>
        </div>
    </main>
    @include('layouts.footer')
@endsection
@endif