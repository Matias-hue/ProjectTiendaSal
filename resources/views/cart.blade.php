@extends('layouts.app')
@section('content')
<?php
session_start();
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $producto = [
        "id" => $_POST['id'],
        "nombre" => $_POST['nombre'],
        "precio" => $_POST['precio'],
        "cantidad" => 1
    ];
    array_push($_SESSION['carrito'], $producto);
}
?>
@include('layouts.header')
<main class="px-8">
    <h2 class="titulo-carrito">Carrito de Compras</h2>
    <div class="carrito-contenedor">
        <?php if (empty($_SESSION['carrito'])): ?>
            <p class="mensaje-vacio">Tu carrito está vacío.</p>
        <?php else: ?>
            <ul class="lista-productos">
                <?php foreach ($_SESSION['carrito'] as $item): ?>
                    <li class="producto-item">
                        <span><?php echo $item['nombre']; ?></span>
                        <span class="precio-item">\$<?php echo $item['precio']; ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
            <a href="checkout.php" class="btn-proceder">Proceder al Pago</a>
        <?php endif; ?>
    </div>
</main>
@include('layouts.footer')
@endsection