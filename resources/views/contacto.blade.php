@include('layouts.header')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}"/>
</head>
<body>
    <div class="py-4 px-8 text-sm text-gray-500">Contacto</div>
    <div class="container-contacto">
        <div class="cuadro-contacto">      
            <div class="card-body-contacto">
                <form method="POST">
                    @csrf
                    
                    <div class="cajas-contacto">
                        <label for="name" class="col-form-label">{{ __('Nombre:') }}</label>
                        <div class="input-contacto">
                            <input id="name" type="text" class="form-control-contacto @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                        </div>
                    </div>

                    <div class="cajas-contacto">
                        <label for="email" class="col-form-label">{{ __('Email:') }}</label>
                        <div class="input-contacto">
                            <input id="email" type="email" class="form-control-contacto @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                        </div>
                    </div>
                    
                    <div class="cajas-contacto">
                        <label for="phone" class="col-form-label">{{ __('Teléfono (Opcional):') }}</label>
                        <div class="input-contacto">
                            <input id="phone" type="text" class="form-control-contacto" name="phone" value="{{ old('phone') }}">
                        </div>
                    </div>

                    <div class="cajas-contacto">
                        <label for="message" class="col-form-label">{{ __('Mensaje (Opcional):') }}</label>
                        <div class="input-contacto">
                            <textarea id="message" class="form-control-contacto" name="message">{{ old('message') }}</textarea>
                        </div>
                    </div>

                    <div class="btn-enviar-mensaje-div">
                        <div class="btn-enviar-mensaje">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Enviar') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div> 
    </div>
    @include('layouts.footer')
</body>
</html>