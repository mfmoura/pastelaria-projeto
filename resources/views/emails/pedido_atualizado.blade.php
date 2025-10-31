<h1>Olá {{ $cliente->nome }},</h1>

<p>Seu pedido (ID: {{ $pedido->id }}) foi atualizado com sucesso!</p>

<ul>
@foreach($produtos as $produto)
    <li>{{ $produto->nome }} - {{ $produto->pivot->quantidade }}x - R$ {{ number_format($produto->pivot->valor_unitario, 2, ',', '.') }}</li>
@endforeach
</ul>

<p><strong>Total: R$ {{ number_format($total, 2, ',', '.') }}</strong></p>

<p>Obrigado por comprar na nossa pastelaria!</p>
