@if(auth()->check() && auth()->user()->role === 'admin')
    <div class="dashboard-content">
        <h2 class="dashboard-title">📊 Dashboard</h2>
        <ul class="dashboard-list">
            <li><a href="{{ route('resumen') }}" class="dashboard-link">📈 Resumen</a></li>
            <li>
                <a href="{{ route('inventario') }}" class="dashboard-link">
                    📦 Inventario
                    @php
                        $lowStockCount = App\Models\Producto::where('stock', '<=', 100)->count();
                    @endphp
                    <span class="badge" id="inventario-badge">{{ $lowStockCount > 0 ? $lowStockCount : '' }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('pedidos.index') }}" class="dashboard-link">
                    🛒 Pedidos
                    @php
                        $pendingOrders = App\Models\Order::where('status', 'Pendiente')->count();
                    @endphp
                    <span class="badge" id="pedidos-badge">{{ $pendingOrders > 0 ? $pendingOrders : '' }}</span>
                </a>
            </li>
            <li><a href="{{ route('usuarios.index') }}" class="dashboard-link">👥 Usuarios</a></li>
            <li>
                <a href="{{ route('alertas') }}" class="dashboard-link">
                    📢 Alertas 
                    <span class="badge" id="alertas-badge">
                        @php
                            $totalAlertas = App\Http\Controllers\AlertaController::getTotalAlertas();
                        @endphp
                        {{ $totalAlertas > 0 ? $totalAlertas : '' }}
                    </span>
                </a>
            </li>
            <li><a href="{{ route('registro') }}" class="dashboard-link">⚙️ Logs de actividad</a></li>
        </ul>
    </div>
@endif