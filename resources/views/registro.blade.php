@extends('layouts.app')

@section('content')
    @include('layouts.header')
    <main class="container">
        <h1 class="titulo-registro">📋 Registro de Actividades</h1>
        <div class="card">
            <input type="text" id="search-registro" name="search" value="{{ $search ?? '' }}" placeholder="Buscar por usuario..." class="search-input">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Acción</th>
                        <th>Descripción</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                        <tr>
                            <td>{{ $log->id }}</td>
                            <td>{{ $log->user->name ?? 'N/A' }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $log->action)) }}</td>
                            <td>{{ $log->description }}</td>
                            <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty">No hay registros disponibles.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="pagination">
                {{ $logs->appends(['search' => $search])->links() }}
            </div>
        </div>
    </main>
    @include('layouts.footer')
@endsection