<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::all();
        return view('inventario', compact('productos')); 
    }

    public function publicIndex()
    {
        $productos = Producto::all();
        return view('productos', compact('productos'));
    }

    public function store(Request $request) {

        $request->validate([
            'nombre' => 'required|string|max:255',
            'tamaño' => 'required|string|max:255',
            'precio' => 'required|numeric',
            'stock' => 'nullable|integer',
        ]);

        Producto::create([
            'nombre' => $request->nombre,
            'tamaño' => $request->tamaño,
            'precio' => $request->precio,
            'stock' => $request->stock ?? 0,
        ]);

        return redirect()->back()->with('success', 'Producto agregado con èxito.');
    }

    public function update(Request $request, $id) {
        
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'tamaño' => 'required|string|max:255',
            'precio' => 'required|numeric',
            'stock' => 'nullable|integer',
        ]);

        $producto = Producto::findOrFail($id);

        $producto->update($validated);

        return redirect()->back()->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy($id) {
        $producto = Producto::findOrFail($id);
        $producto->delete();

        return response()->json(['success' => 'Producto eliminado correctamente.']);
    }

}