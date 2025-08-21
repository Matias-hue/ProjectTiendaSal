@extends('layouts.app')

@section('content')
    @include('layouts.header')
    <main class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-6">📈 Resumen del Mes</h1>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
   
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-4">Productos Vendidos en {{ \Carbon\Carbon::now()->format('F Y') }}</h2>
                <canvas id="ventasPorDiaChart"></canvas>
            </div>
  
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-4">Producto Más Vendido</h2>
                <canvas id="productoMasVendidoChart"></canvas>
            </div>
   
            <div class="bg-white p-6 rounded-lg shadow lg:col-span-2">
                <h2 class="text-xl font-semibold mb-4">Todos los Productos Vendidos en {{ \Carbon\Carbon::now()->format('F Y') }}</h2>
                <canvas id="todosProductosChart"></canvas>
            </div>
        </div>
    </main>
    @include('layouts.footer')

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
 
    <script>
        window.chartLabels = @json($labels ?? []);
        window.chartData = @json($data ?? []);
        window.productoMasVendido = @json($productoMasVendido ?? null);
        window.productosLabels = @json($productosLabels ?? []);
        window.productosData = @json($productosData ?? []);
    </script>
@endsection