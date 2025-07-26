<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AlertaController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

// Rutas generales
Route::get('/', fn() => view('index'))->name('index');

Route::get('/home', fn() => redirect()->route('index'))->name('home');

Route::get('/productos', [ProductoController::class, 'publicIndex'])->name('productos');

Route::get('/ubicacion', fn() => view('ubicacion'))->name('ubicacion');

// Rutas del carrito
Route::get('/carrito', [CartController::class, 'index'])->name('cart');
Route::post('/carrito/add', [CartController::class, 'add'])->name('cart.add')->middleware('auth');
Route::delete('/carrito/remove/{index}', [CartController::class, 'remove'])->name('cart.remove')->middleware('auth');
Route::post('/carrito/checkout', [CartController::class, 'checkout'])->name('checkout')->middleware('auth');

// Ruta de resumen
Route::get('/resumen', [App\Http\Controllers\ResumenController::class, 'index'])->name('resumen');

// Rutas de inventario (admin)
Route::get('/inventario', [ProductoController::class, 'index'])->name('inventario');
Route::post('/productos', [ProductoController::class, 'store'])->name('productos.store');
Route::put('/productos/{id}', [ProductoController::class, 'update'])->name('productos.update');
Route::delete('/productos/{id}', [ProductoController::class, 'destroy'])->name('productos.destroy');

// Rutas de pedidos (admin)
Route::get('/pedidos', [OrderController::class, 'index'])->name('pedidos.index');
Route::patch('/pedidos/{id}/complete', [OrderController::class, 'complete'])->name('pedidos.complete');
Route::patch('/pedidos/{id}/cancel', [OrderController::class, 'cancel'])->name('pedidos.cancel');

// Rutas de usuarios (admin)
Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios');

// Rutas de alertas (admin)
Route::get('/alertas', [AlertaController::class, 'index'])->name('alertas');

// Rutas de registro (admin)
Route::get('/registro', [ActivityLogController::class, 'index'])->name('registro');

Auth::routes();