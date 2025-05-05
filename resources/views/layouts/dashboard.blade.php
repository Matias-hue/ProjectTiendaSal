@if(auth()->check() && auth()->user()->role === 'admin')
    <aside class="dashboard-sidebar bg-gray-200 p-4">
        <div class="dashboard-content">
            <h2 class="text-2xl font-bold mb-6">📊 Dashboard</h2>
            <ul class="space-y-4 text-lg">
                <li><a href="#" class="text-gray-700 hover:text-blue-600">📈 Resumen</a></li>
                <li><a href="{{ route('inventario') }}" class="text-gray-700 hover:text-blue-600">📦 Inventario</a></li>
                <li><a href="#" class="text-gray-700 hover:text-blue-600">🛒 Pedidos</a></li>
                <li><a href="{{ route('usuarios') }}" class="text-gray-700 hover:text-blue-600">👥 Usuarios</a></li>
                <li><a href="#" class="text-gray-700 hover:text-blue-600">📢 Alertas</a></li>
                <li><a href="{{ route('registro') }}" class="text-gray-700 hover:text-blue-600">⚙️ Logs de actividad</a></li>
            </ul>
        </div>
    </aside>
@endif