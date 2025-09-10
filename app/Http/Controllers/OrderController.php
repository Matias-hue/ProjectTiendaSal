<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Producto;
use App\Models\User;
use App\Traits\LogActivity;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderStatusUpdated;
use Illuminate\Support\Facades\Schema;

class OrderController extends Controller
{
    use LogActivity;

    public function index(Request $request)
    {
        $query = $request->input('search');
        $hasDireccion = Schema::hasColumn('users', 'address');

        $pedidos = Order::with(['user', 'items.product'])
            ->when($query, function ($queryBuilder, $search) use ($hasDireccion) {
                return $queryBuilder->whereHas('user', function ($q) use ($search, $hasDireccion) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                    if ($hasDireccion) {
                        $q->orWhere('address', 'like', "%{$search}%");
                    }
                });
            })
            ->latest()
            ->paginate(10)
            ->appends(['search' => $query]);

        if ($request->ajax()) {
            return response()->json([
                'data' => $pedidos->items(),
                'links' => $pedidos->links()->toHtml(),
            ]);
        }

        return view('pedidos', compact('pedidos'));
    }

    public function show($id)
    {   
        $pedido = Order::with(['user', 'items.product'])->findOrFail($id);
        return view('carpeta-pedidos.pedidos-show', compact('pedido')); 
    }

    public function create()
    {
        $usuarios = User::all();
        $productos = Producto::all();
        return view('carpeta-pedidos.pedidos-create', compact('usuarios', 'productos')); 
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

            if ($order->user) {
                Mail::to($order->user->email)->send(new OrderStatusUpdated($order));
            }

            DB::commit();

            if ($request->has('is_admin')) {
                return redirect()->route('pedidos.index')->with('success', 'Pedido creado con éxito.');
            }

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
        return view('carpeta-pedidos.pedidos-edit', compact('pedido', 'productos')); 
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

            if ($order->user) {
                Mail::to($order->user->email)->send(new OrderStatusUpdated($order));
            }

            DB::commit();
            return response()->json(['success' => 'Pedido actualizado correctamente.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al actualizar el pedido: ' . $e->getMessage()], 500);
        }
    }

    public function complete($id)
    {
        \Log::debug("Intentando completar pedido ID: {$id}");
        try {
            $order = Order::findOrFail($id);
            if ($order->status !== 'Pendiente') {
                return response()->json(['error' => 'Solo se pueden completar pedidos pendientes.'], 400);
            }
            $order->update(['status' => 'Completado']);
            $this->logActivity('completar_pedido', "Completó el pedido #{$id}");

            if ($order->user) {
                Mail::to($order->user->email)->send(new OrderStatusUpdated($order));
            }

            \Log::info("Pedido completado ID: {$id}");
            return response()->json(['success' => 'Pedido marcado como completado.', 'status' => 'Completado']);
        } catch (\Exception $e) {
            \Log::error("Error al completar pedido ID: {$id}, Error: {$e->getMessage()}");
            return response()->json(['error' => 'Error al completar el pedido: ' . $e->getMessage()], 500);
        }
    }

    public function cancel($id)
    {
        \Log::debug("Intentando cancelar pedido ID: {$id}");
        try {
            $order = Order::with('items.product')->findOrFail($id);
            if ($order->status !== 'Pendiente') {
                \Log::warning("Intento de cancelar pedido no pendiente ID: {$id}, Estado: {$order->status}");
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

            if ($order->user) {
                Mail::to($order->user->email)->send(new OrderStatusUpdated($order));
            }

            \Log::info("Pedido cancelado ID: {$id}");
            return response()->json(['success' => 'Pedido cancelado correctamente.', 'status' => 'Cancelado']);
        } catch (\Exception $e) {
            \Log::error("Error al cancelar pedido ID: {$id}, Error: {$e->getMessage()}");
            return response()->json(['error' => 'Error al cancelar el pedido: ' . $e->getMessage()], 500);
        }
    }

    public function pdf($id)
    {
        try {
            $pedido = Order::with(['user', 'items.product'])->findOrFail($id);
            $pdf = Pdf::loadView('carpeta-pedidos.pedidos-pdf', compact('pedido'));
            return $pdf->download('pedido-' . $pedido->id . '.pdf');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al generar el PDF: ' . $e->getMessage()], 500);
        }
    }

    public function userOrders()
    {
        $pedidos = Order::with(['items.product'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);
        return view('mis-pedidos', compact('pedidos'));
    }
}