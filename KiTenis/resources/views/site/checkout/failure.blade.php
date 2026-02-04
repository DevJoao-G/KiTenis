@extends('layouts.app')

@section('title', 'Pagamento não concluído')

@section('content')
<div class="container py-5">
    <div class="alert alert-danger shadow-sm">
        <h1 class="h4 mb-2">Pagamento não concluído ❌</h1>
        <p class="mb-0">O pagamento foi recusado ou cancelado. Você pode tentar novamente.</p>
    </div>

    <a class="btn btn-success mt-3" href="{{ route('cart.index') }}">Voltar para o carrinho</a>
</div>
@endsection
