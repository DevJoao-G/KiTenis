@extends('layouts.app')

@section('title', 'Carrinho')

@section('content')
@php
    $shippingFreeFrom = 299.00;
    $shippingValue = 0.00;

    $isFreeShipping = ($total ?? 0) >= $shippingFreeFrom;
    $shippingLabel = $isFreeShipping ? 'Grátis' : ('R$ ' . number_format($shippingValue, 2, ',', '.'));

    $finalTotal = ($total ?? 0) + ($isFreeShipping ? 0 : $shippingValue);

    $installments = 10;
    $installmentValue = $installments > 0 ? ($finalTotal / $installments) : $finalTotal;
@endphp

<div class="container py-5"
     id="cartRoot"
     data-free-from="{{ $shippingFreeFrom }}"
     data-cart-link="{{ route('cart.index') }}"
>

    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="fw-bold mb-0">Carrinho de Compras</h1>

        <span class="badge bg-success" id="cartCountBadge">
            {{ $count ?? 0 }} item(ns)
        </span>
    </div>

    <div id="cartFlash"></div>

    @if (empty($items))
        <div class="alert alert-warning" id="cartEmptyBox">
            Seu carrinho está vazio.
            <a class="alert-link" href="{{ route('products.index') }}">Ver produtos</a>
        </div>
    @else
        <div class="row g-4 align-items-start" id="cartContent">

            {{-- ITENS (ESQUERDA) --}}
            <div class="col-lg-8" id="cartItemsCol">
                @foreach ($items as $item)
                    @php $product = $item['product']; @endphp

                    <div class="card border-0 shadow-sm mb-3 cart-item"
                         data-key="{{ $item['key'] }}"
                         data-update-url="{{ route('cart.update', $item['key']) }}"
                         data-remove-url="{{ route('cart.remove', $item['key']) }}"
                    >
                        <div class="card-body">
                            <div class="d-flex gap-3 align-items-center">

                                <div style="width: 92px; min-width: 92px;">
                                    <img
                                        src="{{ $product->image_url }}"
                                        alt="{{ $product->name }}"
                                        class="img-fluid rounded"
                                        style="width: 92px; height: 92px; object-fit: cover;"
                                    >
                                </div>

                                <div class="flex-grow-1">
                                    <div class="fw-semibold">{{ $product->name }}</div>
                                    <div class="text-muted small">{{ $product->brand ?? 'Marca' }}</div>

                                    <div class="text-muted small">
                                        Tamanho: <strong>{{ $item['size'] }}</strong>
                                        <span class="mx-1">|</span>
                                        Cor: <strong>{{ $item['color'] }}</strong>
                                    </div>

                                    <div class="mt-2 fw-bold">
                                        <span class="unit-value" data-unit="{{ $item['unit'] }}">
                                            R$ {{ number_format($item['unit'], 2, ',', '.') }}
                                        </span>
                                    </div>
                                </div>

                                <div class="text-end">

                                    {{-- Remover (AJAX) --}}
                                    <button type="button" class="btn btn-link text-muted p-0 btn-remove" title="Remover">
                                        <i class="bi bi-trash3"></i>
                                    </button>

                                    <div class="mt-3">
                                        <div class="input-group input-group-sm justify-content-end" style="width: 120px;">
                                            <button class="btn btn-outline-secondary btn-dec" type="button" aria-label="Diminuir">−</button>
                                            <input type="text" class="form-control text-center qty-value" value="{{ $item['qty'] }}" readonly>
                                            <button class="btn btn-outline-secondary btn-inc" type="button" aria-label="Aumentar">+</button>
                                        </div>

                                        <div class="text-muted small mt-1">
                                            Subtotal:
                                            <strong class="subtotal-value" data-subtotal="{{ $item['subtotal'] }}">
                                                R$ {{ number_format($item['subtotal'], 2, ',', '.') }}
                                            </strong>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- RESUMO (DIREITA) --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Resumo do Pedido</h5>

                        <div class="d-flex justify-content-between text-muted mb-2">
                            <span>Subtotal</span>
                            <span id="summarySubtotal">R$ {{ number_format($total, 2, ',', '.') }}</span>
                        </div>

                        <div class="d-flex justify-content-between text-muted mb-3">
                            <span>Frete</span>
                            <span id="summaryShipping" class="{{ $isFreeShipping ? 'text-success' : '' }}">
                                {{ $shippingLabel }}
                            </span>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-semibold">Total</span>
                            <span class="fw-bold fs-4" id="summaryTotal">
                                R$ {{ number_format($finalTotal, 2, ',', '.') }}
                            </span>
                        </div>

                        <div class="text-muted small mb-4" id="summaryInstallments">
                            ou {{ $installments }}x de R$ {{ number_format($installmentValue, 2, ',', '.') }} sem juros
                        </div>

                        <button class="btn btn-success w-100 fw-semibold mb-2" type="button" disabled title="Checkout não implementado">
                            Finalizar Compra <i class="bi bi-arrow-right ms-1"></i>
                        </button>

                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary w-100">
                            Continuar Comprando
                        </a>

                        <div class="text-muted small mt-3">
                            <i class="bi bi-truck me-1"></i>
                            Frete grátis para compras acima de R$ {{ number_format($shippingFreeFrom, 2, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const root = document.getElementById('cartRoot');
    if (!root) return;

    const flash = document.getElementById('cartFlash');
    const countBadge = document.getElementById('cartCountBadge');

    const summarySubtotal = document.getElementById('summarySubtotal');
    const summaryShipping = document.getElementById('summaryShipping');
    const summaryTotal = document.getElementById('summaryTotal');
    const summaryInstallments = document.getElementById('summaryInstallments');

    const money = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' });

    function showFlash(type, text) {
        if (!flash) return;
        flash.innerHTML = `
            <div class="alert alert-${type} py-2">
                ${text}
            </div>
        `;
        setTimeout(() => { flash.innerHTML = ''; }, 2200);
    }

    function setLoading(card, isLoading) {
        if (!card) return;
        card.style.opacity = isLoading ? '0.6' : '1';
        card.style.pointerEvents = isLoading ? 'none' : 'auto';
    }

    // ✅ Atualiza o badge do carrinho no HEADER em tempo real
    function updateNavbarCartBadge(count) {
        const cartUrl = root.getAttribute('data-cart-link');
        if (!cartUrl) return;

        const cartLink =
            document.querySelector(`nav a[href="${cartUrl}"]`) ||
            document.querySelector(`a[href="${cartUrl}"]`);

        if (!cartLink) return;

        // tenta pegar qualquer badge existente dentro do link
        let badge = cartLink.querySelector('span.badge');

        if (!count || count <= 0) {
            if (badge) badge.remove();
            return;
        }

        // se não existe, cria com as classes do seu navbar
        if (!badge) {
            badge = document.createElement('span');
            badge.className = 'position-absolute top-0 start-100 translate-middle badge d-none d-lg-inline rounded-pill bg-success';
            cartLink.appendChild(badge);
        }

        badge.textContent = String(count);
    }

    function ensureEmptyBox() {
        let box = document.getElementById('cartEmptyBox');
        if (box) return box;

        box = document.createElement('div');
        box.className = 'alert alert-warning';
        box.id = 'cartEmptyBox';
        box.innerHTML = `Seu carrinho está vazio. <a class="alert-link" href="{{ route('products.index') }}">Ver produtos</a>`;
        root.appendChild(box);
        return box;
    }

    function updateSummary(data) {
        if (!data) return;

        const count = data.count ?? 0;

        if (countBadge) countBadge.textContent = `${count} item(ns)`;

        // ✅ Atualiza o número do carrinho no header
        updateNavbarCartBadge(count);

        if (summarySubtotal) summarySubtotal.textContent = money.format(data.total ?? 0);

        if (summaryShipping) {
            const isFree = !!data.isFreeShipping;
            summaryShipping.textContent = isFree ? 'Grátis' : money.format(data.shipping ?? 0);
            summaryShipping.classList.toggle('text-success', isFree);
        }

        if (summaryTotal) summaryTotal.textContent = money.format(data.finalTotal ?? 0);

        if (summaryInstallments) {
            const inst = data.installments ?? 10;
            const instVal = data.installmentValue ?? 0;
            summaryInstallments.textContent = `ou ${inst}x de ${money.format(instVal)} sem juros`;
        }

        // Se ficar vazio, remove conteúdo e mostra box
        if (data.empty) {
            const cartContent = document.getElementById('cartContent');
            if (cartContent) cartContent.remove();
            ensureEmptyBox();
        }
    }

    async function send(url, method, payload) {
        const res = await fetch(url, {
            method,
            headers: {
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: payload ? JSON.stringify(payload) : null
        });

        const data = await res.json().catch(() => ({}));
        if (!res.ok) {
            const msg = data?.error || 'Não foi possível atualizar o carrinho.';
            throw new Error(msg);
        }
        return data;
    }

    function handleCardClick(e) {
        const card = e.target.closest('.cart-item');
        if (!card) return;

        const updateUrl = card.getAttribute('data-update-url');
        const removeUrl = card.getAttribute('data-remove-url');

        const btnInc = e.target.closest('.btn-inc');
        const btnDec = e.target.closest('.btn-dec');
        const btnRemove = e.target.closest('.btn-remove');

        if (!btnInc && !btnDec && !btnRemove) return;

        e.preventDefault();

        if (btnRemove) {
            setLoading(card, true);
            send(removeUrl, 'DELETE')
                .then(data => {
                    if (data.removed) {
                        card.remove();
                        showFlash('success', 'Item removido do carrinho.');
                    }
                    updateSummary(data);
                })
                .catch(err => showFlash('danger', err.message))
                .finally(() => setLoading(card, false));

            return;
        }

        const action = btnInc ? 'inc' : 'dec';

        setLoading(card, true);
        send(updateUrl, 'PATCH', { action })
            .then(data => {
                if (!data.ok) {
                    showFlash('danger', data.error || 'Não foi possível atualizar.');
                    return;
                }

                // Atualiza qty
                const qtyEl = card.querySelector('.qty-value');
                if (qtyEl && typeof data.qty !== 'undefined') qtyEl.value = data.qty;

                // Atualiza subtotal do item
                const subEl = card.querySelector('.subtotal-value');
                if (subEl && typeof data.itemSubtotal !== 'undefined') {
                    subEl.textContent = money.format(data.itemSubtotal);
                    subEl.setAttribute('data-subtotal', String(data.itemSubtotal));
                }

                updateSummary(data);
            })
            .catch(err => showFlash('danger', err.message))
            .finally(() => setLoading(card, false));
    }

    // Listener único (delegação)
    root.addEventListener('click', handleCardClick);
});
</script>
@endpush
