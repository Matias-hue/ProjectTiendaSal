<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pedido #{{ $pedido->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 1in;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 1rem;
        }
        .header img {
            width: 2in;
            margin-bottom: 0.5rem;
        }
        .header h1 {
            font-size: 1.5rem;
            margin: 0.25rem 0;
        }
        .header h2 {
            font-size: 1.2rem;
            color: #555;
        }
        h3 {
            font-size: 1.1rem;
            margin-top: 1rem;
            margin-bottom: 0.5rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        th, td {
            border: 1px solid #000;
            padding: 0.5rem;
            text-align: left;
            font-size: 0.9rem;
        }
        th {
            background-color: #f3f4f6;
            font-weight: bold;
        }
        .total {
            margin-top: 1rem;
            font-weight: bold;
            font-size: 1rem;
        }
        .total p {
            margin: 0.25rem 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/logo.png') }}" alt="Sal La Isabela">
        <h1>Sal La Isabela</h1>
        <h2>Pedido #{{ $pedido->id }}</h2>
    </div>
    <h3>Datos del Cliente</h3>
    <p><strong>Nombre:</strong> {{ $pedido->user->name }}</p>
    <p><strong>Email:</strong> {{ $pedido->user->email }}</p>
    <p><strong>Teléfono:</strong> {{ $pedido->user->phone ?? 'No especificado' }}</p>
    <p><strong>Dirección:</strong> {{ $pedido->user->address ?? 'No especificado' }}</p>
    <h3>Productos</h3>
    <table>
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
                    <td>{{ $item->product->nombre }} ({{ $item->product->tamaño }})</td>
                    <td>{{ $item->quantity }}</td>
                    <td>${{ number_format($item->precio, 2) }}</td>
                    <td>${{ number_format($item->quantity * $item->precio, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="total">
        <p><strong>Total:</strong> ${{ number_format($pedido->total, 2) }}</p>
        <p><strong>Fecha:</strong> {{ $pedido->created_at->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>