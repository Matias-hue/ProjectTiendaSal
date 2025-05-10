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
        if ($order->status !== 'Pendiente') {
            return response()->json(['error' => 'Solo se pueden completar pedidos pendientes.'], 400);
        }
        $order->update(['status' => 'Completado']);
        return response()->json(['success' => 'Pedido marcado como completado.', 'status' => 'Completado']);
    }

    public function cancel($id)
    {
        $order = Order::with('items.product')->findOrFail($id);
        if ($order->status !== 'Pendiente') {
            return response()->json(['error' => 'Solo se pueden cancelar pedidos pendientes.'], 400);
        }

        foreach ($order->items as $item) {
            $producto = $item->product;
            $producto->stock += $item->quantity;
            $producto->save();
        }

        $order->update(['status' => 'Cancelado']);

        return response()->json(['success' => 'Pedido cancelado correctamente.', 'status' => 'Cancelado']);
    }
}