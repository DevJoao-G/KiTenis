@extends('layouts.app')

@section('title', 'Pagamento aprovado')

@section('content')
<div class="container py-5">
    <div class="alert alert-success shadow-sm">
        <h1 class="h4 mb-2">Pagamento aprovado ✅</h1>

        @if(!empty($warning))
            <p class="mb-0">{{ $warning }}</p>
        @elseif($order)
            <p class="mb-0">
                Pedido criado com sucesso: <strong>#{{ $order->id }}</strong>.
                Você pode acompanhar em <a href="{{ route('orders.show', $order->id) }}">detalhes do pedido</a>
                ou em <a href="{{ route('orders.index') }}">Meus pedidos</a>.
            </p>
        @else
            <p class="mb-0">
                Pagamento retornou como sucesso, mas não foi possível gerar seu pedido.
                Volte ao carrinho e tente novamente.
            </p>
        @endif
    </div>

    <div class="d-flex gap-2 flex-wrap">
        <a class="btn btn-success" href="{{ route('home') }}">Voltar para a loja</a>
        <a class="btn btn-outline-secondary" href="{{ route('cart.index') }}">Ir para o carrinho</a>
    </div>
</div>
@endsection
