<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function index()
    {
        $carrito = session('carrito', []);
        Log::info('Carrito en index:', ['carrito' => $carrito]);
        return view('cart', compact('carrito'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:productos,id',
            'nombre' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'cantidad' => 'required|integer|min:1',
        ]);

        $producto = Producto::findOrFail($request->id);
        if ($producto->stock < $request->cantidad) {
            Log::warning('Stock insuficiente', ['producto_id' => $producto->id, 'stock' => $producto->stock, 'cantidad' => $request->cantidad]);
            return redirect()->route('productos')->with('error', 'Stock insuficiente para ' . $producto->nombre);
        }

        $carrito = session('carrito', []);
        $index = array_search($producto->id, array_column($carrito, 'id'));

        if ($index !== false) {
            $carrito[$index]['cantidad'] += $request->cantidad;
        } else {
            $carrito[] = [
                'id' => $producto->id,
                'nombre' => $producto->nombre,
                'precio' => $producto->precio,
                'cantidad' => $request->cantidad,
            ];
        }

        session(['carrito' => $carrito]);
        Log::info('Producto añadido al carrito', ['carrito' => $carrito]);
        return redirect()->route('cart')->with('success', 'Producto añadido al carrito.');
    }

    public function remove($index)
    {
        $carrito = session('carrito', []);
        if (isset($carrito[$index])) {
            unset($carrito[$index]);
            session(['carrito' => array_values($carrito)]);
            Log::info('Producto eliminado del carrito', ['index' => $index, 'carrito' => $carrito]);
        }
        return redirect()->route('cart')->with('success', 'Producto eliminado del carrito.');
    }

    public function checkout(Request $request)
    {
        $carrito = session('carrito', []);
        Log::info('Iniciando checkout', ['carrito' => $carrito]);

        if (empty($carrito)) {
            Log::warning('Carrito vacío en checkout');
            return redirect()->route('cart')->with('error', 'El carrito está vacío.');
        }
        
        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => auth()->id(),
                'total' => collect($carrito)->sum(fn($item) => $item['precio'] * $item['cantidad']),
                'status' => 'Pendiente',
            ]);

            foreach ($carrito as $item) {
                $producto = Producto::findOrFail($item['id']);
                if ($producto->stock < $item['cantidad']) {
                    Log::error('Stock insuficiente en checkout', ['producto_id' => $item['id'], 'stock' => $producto->stock, 'cantidad' => $item['cantidad']]);
                    throw new \Exception("Stock insuficiente para {$producto->nombre}.");
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['cantidad'],
                    'precio' => $item['precio'],
                ]);

                $producto->decrement('stock', $item['cantidad']);
                Log::info('Stock reducido', ['producto_id' => $item['id'], 'nuevo_stock' => $producto->stock]);
            }

            session()->forget('carrito');
            DB::commit();
            Log::info('Checkout completado', ['order_id' => $order->id]);
            return redirect()->route('cart')->with('success', 'Compra realizada con éxito.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en checkout', ['error' => $e->getMessage()]);
            return redirect()->route('cart')->with('error', 'Error al procesar la compra: ' . $e->getMessage());
        }
    }
}