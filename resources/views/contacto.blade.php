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
            <div class="card-body-contacto">
                <form>
                    <div class="row mb-3">
                        <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Nombre:') }}</label>
                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control-contacto @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email:') }}</label>
                        <div class="col-md-6">
                            <input id="email" type="text" class="form-control-contacto">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="phone" class="col-md-4 col-form-label text-md-end">{{ __('Teléfono (Opcional):') }}</label>
                        <div class="col-md-6">
                            <input id="phone" type="text" class="form-control-contacto">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="message" class="col-md-4 col-form-label text-md-end">{{ __('Mensaje (Opcional):') }}</label>
                        <div class="col-md-6">
                            <textarea id="message" class="form-control-contacto"></textarea>
                        </div>
                    </div>

                    <div class="row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Enviar') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
</body>
</html>
@include('layouts.footer')