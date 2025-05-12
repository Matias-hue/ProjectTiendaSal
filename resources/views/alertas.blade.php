@extends('layouts.app')

@section('content')
    @include('layouts.header')
    <main>
        <div class="py-4 px-8 text-sm text-gray-500">Alertas</div>
        <div class="container-pedidos">
        <h2>Alertas del Sistema</h2>
            <div class="card-body-inventario">
                <h3>Pedidos Pendientes</h3>
                @if ($pedidosPendientes->isEmpty())
                    <p>No hay pedidos pendientes</p>
                @else
                    <table>
                        <thead>
                            <tr>ID</tr>
                            <tr>Usuario</tr>
                            <tr>Total</tr>
                            <tr>Estado</tr>
                        </thead>

                        <tbody>
                            <tr></tr>
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </main>
    @include('layouts.footer')
@endsection