@extends('layouts.app')

@section('title', 'Pagamento pendente')

@section('content')
<div class="container py-5">
    <div class="alert alert-warning shadow-sm">
        <h1 class="h4 mb-2">Pagamento pendente ⏳</h1>
        <p class="mb-0">O Mercado Pago marcou seu pagamento como pendente. Assim que for confirmado, atualizaremos o status.</p>
    </div>

    <a class="btn btn-success mt-3" href="{{ route('home') }}">Voltar para a loja</a>
</div>
@endsection
