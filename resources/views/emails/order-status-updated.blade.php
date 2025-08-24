<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Actualización del estado de tu pedido #{{ $order->id }}</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h1>Actualización del estado de tu pedido #{{ $order->id }}</h1>
    
    <p>El estado de tu pedido ha cambiado a: <strong>{{ ucfirst($order->status) }}</strong>.</p>
    
    <p>
        <a href="{{ route('pedidos.show', $order->id) }}" 
           style="display: inline-block; padding: 10px 20px; background-color: #007bff; color: #fff; text-decoration: none; border-radius: 5px;">
            Ver Detalles del Pedido
        </a>
    </p>
    
    <p>Gracias por comprar con nosotros,<br>
    {{ config('app.name') }}</p>
</body>
</html>