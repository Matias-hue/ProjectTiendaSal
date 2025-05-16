<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ResumenController extends Controller
{
    public function index()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $ventasPorDia = OrderItem::whereHas('order', function ($query) use ($startOfMonth, $endOfMonth) {
            $query->where('status', 'Completado')
                  ->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
        })
        ->selectRaw('DATE(order_items.created_at) as fecha, SUM(quantity) as total')
        ->groupBy('fecha')
        ->orderBy('fecha')
        ->get()
        ->mapWithKeys(function ($item) {
            return [$item->fecha => $item->total];
        });

        $labels = [];
        $data = [];
        $currentDate = $startOfMonth->copy();
        while ($currentDate <= $endOfMonth) {
            $fecha = $currentDate->format('Y-m-d');
            $labels[] = $currentDate->format('d');
            $data[] = $ventasPorDia->get($fecha, 0);
            $currentDate->addDay();
        }

        $productoMasVendido = OrderItem::whereHas('order', function ($query) use ($startOfMonth, $endOfMonth) {
            $query->where('status', 'Completado')
                  ->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
        })
        ->join('productos', 'order_items.product_id', '=', 'productos.id')
        ->selectRaw('productos.nombre, SUM(order_items.quantity) as total_vendido')
        ->groupBy('productos.nombre')
        ->orderByDesc('total_vendido')
        ->first();

        $todosProductos = OrderItem::whereHas('order', function ($query) use ($startOfMonth, $endOfMonth) {
            $query->where('status', 'Completado')
                  ->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
        })
        ->join('productos', 'order_items.product_id', '=', 'productos.id')
        ->selectRaw('productos.nombre, SUM(order_items.quantity) as total_vendido')
        ->groupBy('productos.nombre')
        ->orderByDesc('total_vendido')
        ->get();

        $productosLabels = $todosProductos->pluck('nombre')->toArray();
        $productosData = $todosProductos->pluck('total_vendido')->toArray();

        return view('resumen', compact('labels', 'data', 'productoMasVendido', 'productosLabels', 'productosData'));
    }
}