@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 mt-20">
            <div class="card">
                <div class="card-header">{{ __('Registrarse') }}</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}" id="registerForm">
                        @csrf
                        @foreach (['name' => ['label' => 'Nombre', 'type' => 'text', 'autocomplete' => 'name'], 
                                   'email' => ['label' => 'Correo Electrónico', 'type' => 'email', 'autocomplete' => 'email'], 
                                   'phone' => ['label' => 'Teléfono', 'type' => 'text', 'autocomplete' => 'tel', 'placeholder' => '+541234567890'], 
                                   'address' => ['label' => 'Dirección', 'type' => 'text', 'autocomplete' => 'street-address']] as $name => $field)
                            <div class="row mb-3">
                                <label for="{{ $name }}" class="col-md-4 col-form-label text-md-end">{{ __($field['label']) }}</label>
                                <div class="col-md-6">
                                    <input id="{{ $name }}" type="{{ $field['type'] }}" 
                                           class="form-control @error($name) is-invalid @enderror" 
                                           name="{{ $name }}" value="{{ old($name) }}" 
                                           required autocomplete="{{ $field['autocomplete'] }}" 
                                           @if(isset($field['placeholder'])) placeholder="{{ $field['placeholder'] }}" @endif>
                                    @error($name)
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                        @endforeach
                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Contraseña') }}</label>
                            <div class="col-md-6">
                                <input id="password" type="password" 
                                       class="form-control @error('password) is-invalid @enderror" 
                                       name="password" required autocomplete="new-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirmar Contraseña') }}</label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" 
                                       class="form-control" name="password_confirmation" 
                                       required autocomplete="new-password">
                            </div>
                        </div>
                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">{{ __('Registrarse') }}</button>
                                <a href="{{ route('index') }}" class="btn btn-primary">{{ __('Volver') }}</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@vite('resources/js/register.js')
@endsection