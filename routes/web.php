<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;

Route::get('/', function () {
    return view('index');
})->name('index');

Route::get('/home', function () {
    return redirect()->route('index');
})->name('home');

Route::get('/productos', function () { 
    return view('productos');   
})->name('productos');

Route::get('/contacto', function () { 
    return view('contacto'); 
})->name('contacto');

Route::get('/ubicacion', function () { 
    return view('ubicacion'); 
})->name('ubicacion');

Route::get('/carrito', function () { 
    return view('cart'); 
})->name('cart');

Route::get('/inventario', [ProductoController::class, 'index'])->name('inventario');

Route::post('/productos', [ProductoController::class, 'store'])->name('productos.store');
Route::put('/productos/{id}', [ProductoController::class, 'update'])->name('productos.update');
Route::delete('/productos/{id}', [ProductoController::class, 'destroy'])->name('productos.destroy');

Auth::routes();