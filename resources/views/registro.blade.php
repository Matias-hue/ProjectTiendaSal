@extends('layouts.app')
@section('content')
@include('layouts.header') 
    <main>
        <div class="py-4 px-8 text-sm text-gray-500">Registro</div>

        <div class="container-registro">
            <div class="card-body-registro">
                <div class="input-search-registro">
                    <input type="text" id="search-registro" placeholder="Buscar usuario...">
                </div>

                <table class="tabla-registro">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                        </tr>
                    </tbody>                  
                </table>
            </div>
        </div>
    </main>
@include('layouts.footer')
@endsection