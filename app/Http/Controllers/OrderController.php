<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $pedidos = Order::with(['user', 'items.product'])->latest()->get();
        return view('pedidos', compact('pedidos'));
    }

    public function complete($id)
    {
        $order = Order::findOrFail($id);
        $order->update(['status' => 'completed']);
        return redirect()->route('pedidos.index')->with('success', 'Pedido marcado como completado.');
    }

    public function destroy($id)
    {
        $order = Order::with('items.product')->findOrFail($id);

        foreach ($order->items as $item) {
            $producto = $item->product;
            $producto->stock += $item->quantity;
            $producto->save();
        }

        $order->items()->delete();
        $order->delete();

        return redirect()->route('pedidos.index')->with('success', 'Pedido eliminado correctamente.');
    }
}