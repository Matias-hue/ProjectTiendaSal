@extends('layouts.app')
@section('content')
@include('layouts.header') 
<div class="py-4 px-8 text-sm text-gray-500">Productos</div>
<main class="px-8">
    <h2 class="text-3xl font-bold mb-6">Nuestros Productos</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php
        $productos = [
            ["id" => 1, "nombre" => "Sal Fina", "precio" => 1800, "imagen" => "img/LaIsabelaSalFina.jpg"],
            ["id" => 2, "nombre" => "Sal Gruesa", "precio" => 1800, "imagen" => "img/LaIsabelaSalGruesa.jpg"],
            ["id" => 3, "nombre" => "Sal Entrefina", "precio" => 1800, "imagen" => "img/LaIsabelaSalEntrefina.jpg"],
            ["id" => 4, "nombre" => "Sal Condimentada", "precio" => 9000, "imagen" => "img/LaIsabelaSalCondimentada.jpg"]
        ];
        foreach ($productos as $producto) {
            echo "<div class='producto-card'>
                    <img src='{$producto['imagen']}' alt='{$producto['nombre']}' class='producto-imagen'>
                    <h3 class='producto-nombre'>{$producto['nombre']}</h3>
                    <p class='producto-precio'>\$ {$producto['precio']}</p>
                    <form action='cart.php' method='POST'>
                        <input type='hidden' name='id' value='{$producto['id']}'>
                        <input type='hidden' name='nombre' value='{$producto['nombre']}'>
                        <input type='hidden' name='precio' value='{$producto['precio']}'>
                        <button type='submit' class='btn-agregar-carrito'>Agregar al carrito</button>
                    </form>
                  </div>";
        }
        ?>
    </div>
</main>
@include('layouts.footer')
@endsection