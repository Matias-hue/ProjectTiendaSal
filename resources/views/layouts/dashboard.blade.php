@if(auth()->check() && auth()->user()->role === 'admin')
    <aside class="dashboard-sidebar bg-gray-200 p-4">
        <div class="dashboard-content">
            <h2 class="text-2xl font-bold mb-6">📊 Dashboard</h2>
            <ul class="space-y-4 text-lg">
                <li><a href="{{ route('resumen') }}" class="text-gray-700 hover:text-blue-600">📈 Resumen</a></li>
                <li><a href="{{ route('inventario') }}" class="text-gray-700 hover:text-blue-600">📦 Inventario</a></li>
                <li>
                    <a href="{{ route('pedidos.index') }}" class="text-gray-700 hover:text-blue-600">
                        🛒 Pedidos
                        @php
                            $pendingOrders = App\Models\Order::where('status', 'Pendiente')->count();
                        @endphp
                        @if($pendingOrders > 0)
                            <span class="badge inline-block bg-red-500 text-white text-xs rounded-full px-2 py-1 ml-2">{{ $pendingOrders }}</span>
                        @endif
                    </a>
                </li>
                <li><a href="{{ route('usuarios.index') }}" class="text-gray-700 hover:text-blue-600">👥 Usuarios</a></li>
                <li>
                    <a href="{{ route('alertas') }}" class="text-gray-700 hover:text-blue-600">
                        📢 Alertas 
                        @php
                            $totalAlertas = App\Http\Controllers\AlertaController::getTotalAlertas();
                        @endphp
                        @if($totalAlertas > 0)
                            <span class="badge inline-block bg-red-500 text-white text-xs rounded-full px-2 py-1 ml-2">{{ $totalAlertas }}</span>
                        @endif
                    </a>
                </li>
                <li><a href="{{ route('registro') }}" class="text-gray-700 hover:text-blue-600">⚙️ Logs de actividad</a></li>
            </ul>
        </div>
    </aside>
@endif