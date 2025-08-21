<div>
    <h3>Pedido #{{ $pedido->id }}</h3>
    <h4>Datos del Cliente</h4>
    <p><strong>Nombre:</strong> {{ $pedido->user->name }}</p>
    <p><strong>Email:</strong> {{ $pedido->user->email }}</p>
    <p><strong>Teléfono:</strong> {{ $pedido->user->phone ?? 'No especificado' }}</p>
    <p><strong>Dirección:</strong> {{ $pedido->user->address ?? 'No especificado' }}</p>
    <h4>Productos</h4>
    <table class="pedidos-table">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pedido->items as $item)
                <tr>
                    <td>{{ $item->product->nombre }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>${{ number_format($item->precio, 2) }}</td>
                    <td>${{ number_format($item->quantity * $item->precio, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <p><strong>Total:</strong> ${{ number_format($pedido->total, 2) }}</p>
    <p><strong>Fecha:</strong> {{ $pedido->created_at->format('d/m/Y H:i') }}</p>
</div>