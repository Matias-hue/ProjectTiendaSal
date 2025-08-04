<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Traits\LogActivity;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use LogActivity;

    public function index()
    {
        $pedidos = Order::with(['user', 'items.product'])->latest()->get();
        return view('pedidos', compact('pedidos'));
    }

    public function show($id)
    {   
        $pedido = Order::with(['user', 'items.product'])->findOrFail($id);
        return view('pedidos-show', compact('pedido'));
    }

    public function complete($id)
    {
        $order = Order::findOrFail($id);
        if ($order->status !== 'Pendiente') {
            return response()->json(['error' => 'Solo se pueden completar pedidos pendientes.'], 400);
        }
        $order->update(['status' => 'Completado']);
        $this->logActivity('completar_pedido', "Completó el pedido #{$id}");

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
        $this->logActivity('cancelar_pedido', "Canceló el pedido #{$id}");

        return response()->json(['success' => 'Pedido cancelado correctamente.', 'status' => 'Cancelado']);
    }
}