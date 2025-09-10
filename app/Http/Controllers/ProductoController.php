<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Traits\LogActivity;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    use LogActivity;

    public function index()
    {
        $productos = Producto::orderBy('id', 'asc')->get();
        return view('inventario', compact('productos')); 
    }

    public function publicIndex()
    {
        $productos = Producto::all();
        return view('productos', compact('productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'tamaño' => 'required|string|max:255',
            'precio' => 'required|numeric',
            'stock' => 'nullable|integer',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagenPath = null;
        if ($request->hasFile('imagen')) {
            $imagenPath = $request->file('imagen')->store('img', 'public');
        }

        $producto = Producto::create([
            'nombre' => $request->nombre,
            'tamaño' => $request->tamaño,
            'precio' => $request->precio,
            'stock' => $request->stock ?? 0,
            'imagen' => $imagenPath,
        ]);

        $this->logActivity('crear_producto', "Creó el producto {$request->nombre}");

        return redirect()->back()->with('success', 'Producto agregado con éxito.');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'tamaño' => 'required|string|max:255',
            'precio' => 'required|numeric',
            'stock' => 'nullable|integer',
        ]);

        $producto = Producto::findOrFail($id);
        $producto->update($validated);

        $this->logActivity('actualizar_producto', "Actualizó el producto {$producto->nombre}");

        return redirect()->back()->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        $nombre = $producto->nombre;
        $producto->delete();

        $this->logActivity('eliminar_producto', "Eliminó el producto {$nombre}");

        return response()->json(['message' => 'Producto eliminado correctamente.'], 200);
    }
}