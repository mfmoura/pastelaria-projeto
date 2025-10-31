<h1>Olá, {{ $cliente->nome }}</h1>

<p>Recebemos seu pedido #{{ $pedido->id }}:</p>

<ul>
@foreach($produtos as $produto)
    <li>{{ $produto['nome'] }} - Quantidade: {{ $produto['quantidade'] }} - Valor: R$ {{ number_format($produto['valor_unitario'], 2, ',', '.') }}</li>
@endforeach
</ul>

<p><strong>Total: R$ {{ number_format($total, 2, ',', '.') }}</strong></p>

<p>Obrigado por comprar na nossa pastelaria!</p>
