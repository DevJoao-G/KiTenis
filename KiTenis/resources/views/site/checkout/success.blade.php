@extends('layouts.app')

@section('title', 'Pagamento aprovado')

@section('content')
<div class="container py-5">
    <div class="alert alert-success shadow-sm">
        <h1 class="h4 mb-2">Pagamento aprovado ✅</h1>
        <p class="mb-0">Recebemos a confirmação do Mercado Pago. Em breve seu pedido aparecerá em <a href="{{ route('orders.index') }}">Meus pedidos</a> (quando a área de pedidos estiver integrada).</p>
    </div>

    <a class="btn btn-success mt-3" href="{{ route('home') }}">Voltar para a loja</a>
</div>
@endsection
