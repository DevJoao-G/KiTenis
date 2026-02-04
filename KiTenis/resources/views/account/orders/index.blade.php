@extends('layouts.app')

@section('title', 'Meus Pedidos')

@section('content')
<div class="container py-5">

    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
        <div>
            <h1 class="fw-bold mb-1">Meus Pedidos</h1>
            <div class="text-muted">Acompanhe o histórico de compras.</div>
        </div>

        <a href="{{ route('account') }}" class="btn btn-outline-secondary">
            <i class="bi bi-person me-1"></i> Minha Conta
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            @if($orders->isEmpty())
                <div class="alert alert-warning mb-0">
                    <div class="fw-semibold mb-1">Você ainda não possui pedidos.</div>
                    <div class="small text-muted mb-3">
                        Assim que você concluir uma compra, ela aparecerá aqui.
                    </div>
                    <a href="{{ route('products.index') }}" class="btn btn-success">
                        <i class="bi bi-shop me-1"></i> Ver produtos
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr class="text-muted small">
                                <th>#</th>
                                <th>Data</th>
                                <th>Status</th>
                                <th class="text-end">Total</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td class="fw-semibold">#{{ $order->id }}</td>
                                    <td>{{ $order->created_at?->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $order->status ?? '—' }}</span>
                                    </td>
                                    <td class="text-end fw-semibold">
                                        R$ {{ number_format($order->total ?? 0, 2, ',', '.') }}
                                    </td>
                                    <td class="text-end">
                                        <a class="btn btn-sm btn-outline-secondary"
                                           href="{{ route('orders.show', $order->id) }}">
                                            Ver detalhes
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
