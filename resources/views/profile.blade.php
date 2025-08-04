@extends('layouts.app')
@section('content')
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil - {{ config('app.name', 'Sal La Isabela') }}</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
    @include('layouts.header')
    <main>
        <div class="container-registro">
            <h2 class="titulo-registro">Editar Perfil</h2>
            <div class="card-body-registro">
                @if (session('success'))
                    <div class="alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert-error">{{ session('error') }}</div>
                @endif
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="input-search-registro">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', Auth::user()->email) }}" required>
                    </div>
                    <div class="input-search-registro">
                        <label for="phone">Teléfono (incluye código de área)</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', Auth::user()->phone) }}">
                    </div>
                    <div class="input-search-registro">
                        <label for="address">Dirección</label>
                        <input type="text" name="address" id="address" value="{{ old('address', Auth::user()->address) }}">
                    </div>
                    <button type="submit" class="btn-agregar">Guardar Cambios</button>
                </form>
            </div>
        </div>
    </main>
    @include('layouts.footer')
</body>
</html>
@endsection