@extends('layouts.app')

@section('title', 'Detalhes do Pedido')

@section('content')
<div class="container py-5">

    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
        <div>
            <h1 class="fw-bold mb-1">Pedido #{{ $order->id }}</h1>
            <div class="text-muted">Confira os itens e o status do seu pedido.</div>
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

    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start justify-content-between mb-3">
                        <div>
                            <div class="text-muted small">Status</div>
                            <div class="fw-semibold">
                                <span class="badge bg-secondary">{{ $order->status }}</span>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="text-muted small">Data</div>
                            <div class="fw-semibold">{{ $order->created_at?->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>

                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Total</span>
                            <span class="fw-bold">R$ {{ number_format($order->total ?? 0, 2, ',', '.') }}</span>
                        </div>

                        @if($order->paid_at)
                            <div class="d-flex justify-content-between mt-2">
                                <span class="text-muted">Pago em</span>
                                <span class="fw-semibold">{{ $order->paid_at->format('d/m/Y H:i') }}</span>
                            </div>
                        @endif

                        @if($order->payment_method)
                            <div class="d-flex justify-content-between mt-2">
                                <span class="text-muted">Método</span>
                                <span class="fw-semibold text-uppercase">{{ $order->payment_method }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Itens do pedido</h5>

                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr class="text-muted small">
                                    <th>Produto</th>
                                    <th class="text-center">Qtd</th>
                                    <th class="text-end">Preço</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $item->product?->name ?? 'Produto removido' }}</div>
                                            <div class="small text-muted">
                                                @php
                                                    $variant = trim(
                                                        ($item->size ? 'Tam ' . $item->size : '') .
                                                        ($item->color ? ' ' . $item->color : '')
                                                    );
                                                @endphp
                                                {{ $variant !== '' ? $variant : '—' }}
                                            </div>
                                        </td>
                                        <td class="text-center">{{ (int) $item->quantity }}</td>
                                        <td class="text-end">R$ {{ number_format($item->price ?? 0, 2, ',', '.') }}</td>
                                        <td class="text-end fw-semibold">
                                            R$ {{ number_format(($item->price ?? 0) * ($item->quantity ?? 0), 2, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
