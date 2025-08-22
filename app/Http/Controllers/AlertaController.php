<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Producto;
use Illuminate\Http\Request;

class AlertaController extends Controller
{
    public function index()
    {
        $pedidosPendientes = Order::where('status', 'Pendiente')->with('user')->get();
        $productosBajoStock = Producto::where('stock', '<=', 100)->get();

        return view('alertas', compact('pedidosPendientes', 'productosBajoStock'));
    }

    public static function getTotalAlertas()
    {
        $pedidosPendientes = Order::where('status', 'Pendiente')->with('user')->count();
        $productosBajoStock = Producto::where('stock', '<=', 100)->count();

        return $pedidosPendientes + $productosBajoStock;
    }

    public function getTotalAlertasAjax()
    {
        $pedidosPendientes = Order::where('status', 'Pendiente')->count();
        $productosBajoStock = Producto::where('stock', '<=', 100)->count();
        $totalAlertas = $pedidosPendientes + $productosBajoStock;

        return response()->json([
            'lowStockCount' => $productosBajoStock,
            'pendingOrders' => $pedidosPendientes,
            'totalAlertas' => $totalAlertas
        ]);
    }
}