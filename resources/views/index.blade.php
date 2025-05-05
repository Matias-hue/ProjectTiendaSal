@extends('layouts.app')
@section('content')
@include('layouts.header') 

<div class="py-4 px-8 text-sm text-gray-500">Inicio</div>
    <main class="px-8">
        <div class="bg-white p-10 rounded-lg shadow-lg mb-8 text-xl">
            <h2 class="text-3xl font-bold mb-6">Sobre Nosotros</h2>
            <p class="text-gray-700">Emprendimiento familiar dedicado al fraccionamiento de sal de consumo y elaboración de sal condimentada.</p>
            <p class="text-gray-700 mt-4">Ofrecemos un producto natural y económico.</p>
            <blockquote class="italic text-gray-600 mt-4">“La calidad y el sabor se combinan en cada grano de nuestra sal.”</blockquote>
        </div>

        <div class="bg-white p-10 rounded-lg shadow-lg text-xl">
            <h2 class="text-3xl font-bold mb-6">Producto Destacado</h2>
            <p class="text-gray-700">Nuestra sal condimentada: la mejor opción natural y económica.</p>
            <blockquote class="italic text-gray-600 mt-4">“Realza el sabor de tus comidas con el toque perfecto de nuestra sal.”</blockquote>
        </div>
    </main>
@include('layouts.footer')
@endsection