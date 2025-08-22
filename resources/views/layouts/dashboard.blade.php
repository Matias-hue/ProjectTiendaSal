@if(auth()->check() && auth()->user()->role === 'admin')
    <aside class="dashboard-sidebar">
        <div class="dashboard-content">
            <h2 class="dashboard-title">📊 Dashboard</h2>
            <ul class="dashboard-list">
                <li><a href="{{ route('resumen') }}" class="dashboard-link">📈 Resumen</a></li>
                <li><a href="{{ route('inventario') }}" class="dashboard-link">📦 Inventario</a></li>
                <li>
                    <a href="{{ route('pedidos.index') }}" class="dashboard-link">
                        🛒 Pedidos
                        @php
                            $pendingOrders = App\Models\Order::where('status', 'Pendiente')->count();
                        @endphp
                        @if($pendingOrders > 0)
                            <span class="badge">{{ $pendingOrders }}</span>
                        @endif
                    </a>
                </li>
                <li><a href="{{ route('usuarios.index') }}" class="dashboard-link">👥 Usuarios</a></li>
                <li>
                    <a href="{{ route('alertas') }}" class="dashboard-link">
                        📢 Alertas 
                        @php
                            $totalAlertas = App\Http\Controllers\AlertaController::getTotalAlertas();
                        @endphp
                        @if($totalAlertas > 0)
                            <span class="badge">{{ $totalAlertas }}</span>
                        @endif
                    </a>
                </li>
                <li><a href="{{ route('registro') }}" class="dashboard-link">⚙️ Logs de actividad</a></li>
            </ul>
        </div>
    </aside>
@endif