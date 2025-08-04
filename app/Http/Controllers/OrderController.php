<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Producto;
use App\Models\User;
use App\Traits\LogActivity;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

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

    public function create()
    {
        $usuarios = User::all();
        $productos = Producto::all();
        return view('pedidos-create', compact('usuarios', 'productos'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'items' => 'required|array',
                'items.*.product_id' => 'required|exists:productos,id',
                'items.*.quantity' => 'required|integer|min:1',
            ]);

            DB::beginTransaction();
            $total = 0;
            $order = Order::create([
                'user_id' => $request->user_id,
                'total' => 0,
                'status' => 'Pendiente',
            ]);

            foreach ($request->items as $itemData) {
                $producto = Producto::findOrFail($itemData['product_id']);
                if ($producto->stock < $itemData['quantity']) {
                    throw new \Exception("Stock insuficiente para {$producto->nombre}");
                }
                $order->items()->create([
                    'product_id' => $itemData['product_id'],
                    'quantity' => $itemData['quantity'],
                    'precio' => $producto->precio,
                ]);
                $producto->stock -= $itemData['quantity'];
                $producto->save();
                $total += $itemData['quantity'] * $producto->precio;
            }

            $order->update(['total' => $total]);
            $this->logActivity('crear_pedido', "Creó el pedido #{$order->id}");

            DB::commit();
            return response()->json(['success' => 'Pedido creado correctamente.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al crear el pedido: ' . $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        $pedido = Order::with(['user', 'items.product'])->findOrFail($id);
        $productos = Producto::all();
        return view('pedidos-edit', compact('pedido', 'productos'));
    }

    public function update(Request $request, $id)
    {
        try {
            $order = Order::with('items.product')->findOrFail($id);
            if ($order->status !== 'Pendiente') {
                return response()->json(['error' => 'Solo se pueden editar pedidos pendientes.'], 400);
            }

            $request->validate([
                'items' => 'required|array',
                'items.*.product_id' => 'required|exists:productos,id',
                'items.*.quantity' => 'required|integer|min:1',
            ]);

            DB::beginTransaction();
            foreach ($order->items as $item) {
                $item->product->stock += $item->quantity;
                $item->product->save();
            }
            $order->items()->delete();

            $total = 0;
            foreach ($request->items as $itemData) {
                $producto = Producto::findOrFail($itemData['product_id']);
                if ($producto->stock < $itemData['quantity']) {
                    throw new \Exception("Stock insuficiente para {$producto->nombre}");
                }
                $order->items()->create([
                    'product_id' => $itemData['product_id'],
                    'quantity' => $itemData['quantity'],
                    'precio' => $producto->precio,
                ]);
                $producto->stock -= $itemData['quantity'];
                $producto->save();
                $total += $itemData['quantity'] * $producto->precio;
            }

            $order->update(['total' => $total]);
            $this->logActivity('editar_pedido', "Editó el pedido #{$id}");

            DB::commit();
            return response()->json(['success' => 'Pedido actualizado correctamente.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al actualizar el pedido: ' . $e->getMessage()], 500);
        }
    }

    public function complete($id)
    {
        try {
            $order = Order::findOrFail($id);
            if ($order->status !== 'Pendiente') {
                return response()->json(['error' => 'Solo se pueden completar pedidos pendientes.'], 400);
            }
            $order->update(['status' => 'Completado']);
            $this->logActivity('completar_pedido', "Completó el pedido #{$id}");

            return response()->json(['success' => 'Pedido marcado como completado.', 'status' => 'Completado']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al completar el pedido: ' . $e->getMessage()], 500);
        }
    }

    public function cancel($id)
    {
        try {
            $order = Order::with('items.product')->findOrFail($id);
            if ($order->status !== 'Pendiente') {
                return response()->json(['error' => 'Solo se pueden cancelar pedidos pendientes.'], 400);
            }

            foreach ($order->items as $item) {
                $producto = $item->product;
                if ($producto) {
                    $producto->stock += $item->quantity;
                    $producto->save();
                } else {
                    throw new \Exception('Producto no encontrado para el ítem del pedido.');
                }
            }

            $order->update(['status' => 'Cancelado']);
            $this->logActivity('cancelar_pedido', "Canceló el pedido #{$id}");

            return response()->json(['success' => 'Pedido cancelado correctamente.', 'status' => 'Cancelado']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al cancelar el pedido: ' . $e->getMessage()], 500);
        }
    }

    public function pdf($id)
    {
        try {
            $pedido = Order::with(['user', 'items.product'])->findOrFail($id);
            $pdf = Pdf::loadView('pedidos-pdf', compact('pedido'));
            return $pdf->download('pedido-' . $pedido->id . '.pdf');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al generar el PDF: ' . $e->getMessage()], 500);
        }
    }
}