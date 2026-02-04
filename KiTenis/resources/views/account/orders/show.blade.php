@extends('layouts.app')

@section('title', 'Detalhes do Pedido')

@section('content')
<div class="container py-5">

    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
        <div>
            <h1 class="fw-bold mb-1">Pedido #{{ $orderId }}</h1>
            <div class="text-muted">Detalhes do pedido (placeholder).</div>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-bag-check me-1"></i> Voltar aos pedidos
            </a>
            <a href="{{ route('account') }}" class="btn btn-outline-secondary">
                <i class="bi bi-person me-1"></i> Minha Conta
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="alert alert-warning mb-0">
                <div class="fw-semibold mb-1">Detalhes do pedido ainda não implementados.</div>
                <div class="small text-muted">
                    Seu projeto ainda não possui Model/Tabela de pedidos. Quando você criar (ex.: `orders`, `order_items`),
                    eu adapto esse arquivo para mostrar itens, total, frete e status com base no banco.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
