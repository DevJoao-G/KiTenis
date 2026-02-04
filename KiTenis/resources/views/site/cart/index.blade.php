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

    <div class="container py-5" id="cartRoot" data-free-from="{{ $shippingFreeFrom }}"
        data-cart-link="{{ route('cart.index') }}">

        <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2 mb-4">
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

                        <div class="card border-0 shadow-sm mb-3 cart-item" data-key="{{ $item['key'] }}"
                            data-update-url="{{ route('cart.update', $item['key']) }}"
                            data-remove-url="{{ route('cart.remove', $item['key']) }}">
                            <div class="card-body">
                                <div class="row g-3 align-items-start align-items-md-center">

                                    {{-- Imagem --}}
                                    <div class="col-12 col-sm-auto">
                                        <div class="mx-auto mx-sm-0" style="width: 92px; min-width: 92px;">
                                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="img-fluid rounded"
                                                style="width: 92px; height: 92px; object-fit: cover;">
                                        </div>
                                    </div>

                                    {{-- Infos --}}
                                    <div class="col-12 col-sm">
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

                                    {{-- Ações / Quantidade --}}
                                    <div class="col-12 col-md-auto">
                                        <div class="d-flex justify-content-between justify-content-md-end align-items-center">
                                            <div class="text-muted small d-md-none">Ações</div>

                                            {{-- Remover (AJAX) --}}
                                            <button type="button" class="btn btn-link text-muted p-0 btn-remove" title="Remover">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </div>

                                        <div class="mt-3">
                                            <div class="input-group input-group-sm justify-content-start justify-content-md-end" style="width: 140px;">
                                                <button class="btn btn-outline-secondary btn-dec" type="button"
                                                    aria-label="Diminuir">−</button>
                                                <input type="text" class="form-control text-center qty-value"
                                                    value="{{ $item['qty'] }}" readonly>
                                                <button class="btn btn-outline-secondary btn-inc" type="button"
                                                    aria-label="Aumentar">+</button>
                                            </div>

                                            <div class="text-muted small mt-2 text-start text-md-end">
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

                            <div class="d-grid gap-2">
                                <button class="btn btn-success fw-semibold" type="button" data-bs-toggle="modal"
                                    data-bs-target="#checkoutModal">
                                    Finalizar Compra <i class="bi bi-arrow-right ms-1"></i>
                                </button>

                                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                                    Continuar Comprando
                                </a>
                            </div>

                            <div class="text-muted small mt-3">
                                <i class="bi bi-truck me-1"></i>
                                Frete grátis para compras acima de R$ {{ number_format($shippingFreeFrom, 2, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- MODAL CHECKOUT (3 ETAPAS) --}}
            <div class="modal fade checkout-modal" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="checkoutModalLabel">Finalizar Compra</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                        </div>

                        <div class="modal-body">
                            {{-- Stepper --}}
                            <div class="checkout-steps" id="checkoutSteps">
                                <div class="checkout-step is-active" data-step="0">
                                    <span class="step-circle">1</span>
                                    <span class="step-label">Dados pessoais</span>
                                </div>
                                <div class="checkout-separator"></div>
                                <div class="checkout-step" data-step="1">
                                    <span class="step-circle">2</span>
                                    <span class="step-label">Entrega</span>
                                </div>
                                <div class="checkout-separator"></div>
                                <div class="checkout-step" data-step="2">
                                    <span class="step-circle">3</span>
                                    <span class="step-label">Pagamento</span>
                                </div>
                            </div>

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif


                            <form id="checkoutForm" method="POST" action="{{ route('checkout.mercadopago') }}" novalidate>
                                @csrf
                                {{-- ETAPA 1 --}}
                                <div class="checkout-step-pane is-visible" data-pane="0">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <h6 class="fw-bold mb-0">Informações do comprador</h6>
                                            <p class="text-muted small mb-0">Preencha seus dados pessoais para continuar.</p>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Nome completo</label>
                                            <input type="text" name="buyer_name" class="form-control" required>
                                            <div class="invalid-feedback">Informe seu nome completo.</div>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">CPF</label>
                                            <input type="text" name="buyer_cpf" class="form-control" inputmode="numeric" required>
                                            <div class="invalid-feedback">Informe um CPF válido.</div>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Telefone</label>
                                            <input type="text" name="buyer_phone" class="form-control" inputmode="numeric" required>
                                            <div class="invalid-feedback">Informe um telefone válido.</div>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Data de nascimento</label>
                                            <input type="date" name="buyer_birth" class="form-control" required>
                                            <div class="invalid-feedback">Informe sua data de nascimento.</div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end mt-4">
                                        <button type="button" class="btn btn-success fw-semibold" id="btnNextToStep2">
                                            Próximo <i class="bi bi-arrow-right ms-1"></i>
                                        </button>
                                    </div>
                                </div>

                                {{-- ETAPA 2 --}}
                                <div class="checkout-step-pane" data-pane="1">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <h6 class="fw-bold mb-0">Informações da residência</h6>
                                            <p class="text-muted small mb-0">Preencha o endereço de entrega.</p>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">CEP</label>
                                            <input type="text" name="ship_cep" class="form-control" inputmode="numeric" required>
                                            <div class="invalid-feedback">Informe um CEP válido.</div>
                                        </div>

                                        <div class="col-md-8">
                                            <label class="form-label">Endereço</label>
                                            <input type="text" name="ship_street" class="form-control" required>
                                            <div class="invalid-feedback">Informe o endereço.</div>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Número</label>
                                            <input type="text" name="ship_number" class="form-control" required>
                                            <div class="invalid-feedback">Informe o número.</div>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Complemento (apto)</label>
                                            <input type="text" name="ship_complement" class="form-control">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Bairro</label>
                                            <input type="text" name="ship_district" class="form-control" required>
                                            <div class="invalid-feedback">Informe o bairro.</div>
                                        </div>

                                        <div class="col-md-8">
                                            <label class="form-label">Cidade</label>
                                            <input type="text" name="ship_city" class="form-control" required>
                                            <div class="invalid-feedback">Informe a cidade.</div>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">UF</label>
                                            <input type="text" name="ship_uf" class="form-control" required>
                                            <div class="invalid-feedback">Informe a UF (2 letras).</div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between mt-4">
                                        <button type="button" class="btn btn-outline-secondary" id="btnBackToStep1">
                                            <i class="bi bi-arrow-left me-1"></i> Voltar
                                        </button>

                                        <button type="button" class="btn btn-success fw-semibold" id="btnNextToStep3">
                                            Próximo <i class="bi bi-arrow-right ms-1"></i>
                                        </button>
                                    </div>
                                </div>

                                {{-- ETAPA 3 --}}
                                <div class="checkout-step-pane" data-pane="2">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <h6 class="fw-bold mb-0">Informações de pagamento</h6>
                                            <p class="text-muted small mb-0">Escolha como deseja pagar.</p>
                                        </div>

                                        {{-- Opções --}}
                                        <div class="col-12">
                                            <div class="payment-options">
                                                <button type="button" class="payment-option is-active" data-method="pix">
                                                    <i class="bi bi-qr-code me-2"></i> PIX
                                                </button>
                                                <button type="button" class="payment-option" data-method="card">
                                                    <i class="bi bi-credit-card-2-front me-2"></i> Cartão
                                                </button>
                                                <button type="button" class="payment-option" data-method="boleto">
                                                    <i class="bi bi-receipt me-2"></i> Boleto
                                                </button>
                                            </div>

                                            <input type="hidden" name="payment_method" id="payment_method" value="pix">
                                        </div>

                                        {{-- PIX --}}
                                        <div class="col-12 payment-pane is-visible" data-pane-method="pix">
                                            <div class="alert alert-info mb-0">
                                                <i class="bi bi-info-circle me-1"></i>
                                                Ao finalizar, você será redirecionado para o Mercado Pago para concluir o pagamento via PIX.
                                            </div>
                                        </div>

                                        {{-- Cartão (não será enviado se usar redirect Checkout Pro) --}}
                                        <div class="col-12 payment-pane" data-pane-method="card">
                                            <div class="alert alert-warning">
                                                <i class="bi bi-shield-lock me-1"></i>
                                                Para sua segurança, os dados do cartão não serão enviados neste modo de redirecionamento (Checkout Pro).
                                            </div>

                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Número do cartão</label>
                                                    <input type="text" name="card_number" class="form-control" inputmode="numeric">
                                                    <div class="invalid-feedback">Informe um número válido.</div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="form-label">Validade</label>
                                                    <input type="text" name="card_exp" class="form-control" placeholder="MM/AA">
                                                    <div class="invalid-feedback">Informe a validade.</div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="form-label">CVV</label>
                                                    <input type="text" name="card_cvv" class="form-control" inputmode="numeric">
                                                    <div class="invalid-feedback">Informe o CVV.</div>
                                                </div>

                                                <div class="col-md-12">
                                                    <label class="form-label">Nome no cartão</label>
                                                    <input type="text" name="card_name" class="form-control">
                                                    <div class="invalid-feedback">Informe o nome do titular.</div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Boleto --}}
                                        <div class="col-12 payment-pane" data-pane-method="boleto">
                                            <div class="alert alert-info">
                                                <i class="bi bi-info-circle me-1"></i>
                                                Ao finalizar, você será redirecionado para o Mercado Pago para gerar o boleto.
                                            </div>

                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">E-mail para receber o boleto</label>
                                                    <input type="email" name="boleto_email" class="form-control">
                                                    <div class="invalid-feedback">Informe um e-mail válido.</div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="d-flex justify-content-between mt-4">
                                        <button type="button" class="btn btn-outline-secondary" id="btnBackToStep2">
                                            <i class="bi bi-arrow-left me-1"></i> Voltar
                                        </button>

                                        <button type="submit" class="btn btn-success fw-semibold" id="btnFinishCheckout">
                                            Finalizar Pedido
                                        </button>
                                    </div>
                                </div>
                            </form>

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
            const root = document.getElementById('cartRoot');
            if (!root) return;

            const cartLink = root.getAttribute('data-cart-link') || '';

            const freeFrom = Number(root.getAttribute('data-free-from') || '0');
            const shippingValue = 0;

            const flash = document.getElementById('cartFlash');
            const cartCountBadge = document.getElementById('cartCountBadge');

            const summarySubtotal = document.getElementById('summarySubtotal');
            const summaryShipping = document.getElementById('summaryShipping');
            const summaryTotal = document.getElementById('summaryTotal');
            const summaryInstallments = document.getElementById('summaryInstallments');

            const installments = 10;

            function showFlash(type, msg) {
                if (!flash) return;
                flash.innerHTML = `
                    <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                        ${msg}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                    </div>
                `;
            }

            function formatBRL(value) {
                const n = Number(value || 0);
                return n.toLocaleString('pt-BR', {
                    style: 'currency',
                    currency: 'BRL'
                });
            }

            function getCartTotals() {
                let subtotal = 0;
                document.querySelectorAll('.cart-item').forEach((card) => {
                    const subEl = card.querySelector('.subtotal-value');
                    const sub = Number(subEl?.getAttribute('data-subtotal') || 0);
                    subtotal += sub;
                });

                const isFree = subtotal >= freeFrom;
                const ship = isFree ? 0 : shippingValue;
                const total = subtotal + ship;
                const inst = installments > 0 ? (total / installments) : total;

                return {
                    subtotal,
                    ship,
                    total,
                    inst,
                    isFree
                };
            }

            function updateSummaryUI() {
                const t = getCartTotals();
                if (summarySubtotal) summarySubtotal.textContent = formatBRL(t.subtotal);

                if (summaryShipping) {
                    summaryShipping.textContent = t.isFree ? 'Grátis' : formatBRL(t.ship);
                    summaryShipping.classList.toggle('text-success', t.isFree);
                }

                if (summaryTotal) summaryTotal.textContent = formatBRL(t.total);

                if (summaryInstallments) {
                    summaryInstallments.textContent = `ou ${installments}x de ${formatBRL(t.inst)} sem juros`;
                }
            }

            async function postJSON(url, payload) {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(payload || {})
                });

                const data = await res.json().catch(() => null);

                if (!res.ok) {
                    const msg = data?.message || 'Erro inesperado.';
                    throw new Error(msg);
                }

                return data;
            }

            function updateItemUI(card, qty, unit) {
                const qtyEl = card.querySelector('.qty-value');
                const unitEl = card.querySelector('.unit-value');
                const subEl = card.querySelector('.subtotal-value');

                const subtotal = (Number(qty) || 0) * (Number(unit) || 0);

                if (qtyEl) qtyEl.value = qty;
                if (unitEl) unitEl.textContent = formatBRL(unit);
                if (subEl) {
                    subEl.textContent = formatBRL(subtotal);
                    subEl.setAttribute('data-subtotal', String(subtotal));
                }
            }

            function updateCountBadge(count) {
                if (cartCountBadge) {
                    cartCountBadge.textContent = `${count} item(ns)`;
                }
            }

            function removeItemCard(card) {
                card.remove();

                const cards = document.querySelectorAll('.cart-item');
                if (cards.length === 0) {
                    window.location.href = cartLink;
                }
            }

            async function handleCardClick(e) {
                const btn = e.target.closest('button');
                if (!btn) return;

                const card = e.target.closest('.cart-item');
                if (!card) return;

                const unitEl = card.querySelector('.unit-value');
                const unit = Number(unitEl?.getAttribute('data-unit') || 0);

                const qtyEl = card.querySelector('.qty-value');
                const currentQty = Number(qtyEl?.value || 0);

                const updateUrl = card.getAttribute('data-update-url');
                const removeUrl = card.getAttribute('data-remove-url');

                try {
                    if (btn.classList.contains('btn-inc')) {
                        const nextQty = currentQty + 1;

                        const data = await postJSON(updateUrl, {
                            qty: nextQty
                        });

                        updateItemUI(card, data.qty, unit);
                        updateCountBadge(data.count);
                        updateSummaryUI();
                        return;
                    }

                    if (btn.classList.contains('btn-dec')) {
                        const nextQty = Math.max(1, currentQty - 1);

                        const data = await postJSON(updateUrl, {
                            qty: nextQty
                        });

                        updateItemUI(card, data.qty, unit);
                        updateCountBadge(data.count);
                        updateSummaryUI();
                        return;
                    }

                    if (btn.classList.contains('btn-remove')) {
                        const data = await postJSON(removeUrl, {});

                        updateCountBadge(data.count);
                        removeItemCard(card);
                        updateSummaryUI();

                        showFlash('success', 'Item removido do carrinho.');
                        return;
                    }
                } catch (err) {
                    showFlash('danger', err.message || 'Erro ao atualizar carrinho.');
                }
            }

            // ==========================
            // CHECKOUT MODAL: STEPPER + PAGAMENTO
            // ==========================
            const checkoutModalEl = document.getElementById('checkoutModal');
            const checkoutForm = document.getElementById('checkoutForm');

            const stepsEl = document.getElementById('checkoutSteps');
            const stepEls = stepsEl ? stepsEl.querySelectorAll('.checkout-step') : [];
            const separatorEls = stepsEl ? stepsEl.querySelectorAll('.checkout-separator') : [];

            const panes = document.querySelectorAll('.checkout-step-pane');

            const btnNext1 = document.getElementById('btnNextToStep2');
            const btnBack2 = document.getElementById('btnBackToStep1');
            const btnNext2 = document.getElementById('btnNextToStep3');
            const btnBack3 = document.getElementById('btnBackToStep2');
            const btnFinish = document.getElementById('btnFinishCheckout');

            const paymentMethodInput = document.getElementById('payment_method');
            const paymentOptions = document.querySelectorAll('.payment-option');
            const paymentPanes = document.querySelectorAll('.payment-pane');

            let currentStep = 0;

            function setStep(i) {
                currentStep = i;

                // Steps UI
                stepEls.forEach((el, idx) => {
                    el.classList.toggle('is-active', idx === i);
                    el.classList.toggle('is-done', idx < i);
                });

                separatorEls.forEach((sep, idx) => {
                    sep.classList.toggle('is-done', idx < i);
                });

                // Panes
                panes.forEach((pane) => {
                    pane.classList.remove('is-visible');
                });

                const pane = document.querySelector(`.checkout-step-pane[data-pane="${i}"]`);
                pane?.classList.add('is-visible');
            }

            function getPaymentMethod() {
                return paymentMethodInput?.value || 'pix';
            }

            function selectPayment(method) {
                if (!paymentMethodInput) return;
                paymentMethodInput.value = method;

                paymentOptions.forEach((opt) => {
                    opt.classList.toggle('is-active', opt.getAttribute('data-method') === method);
                });

                syncPaymentPanes();
            }

            function syncPaymentPanes() {
                const method = getPaymentMethod();
                paymentPanes.forEach((pane) => {
                    pane.classList.toggle('is-visible', pane.getAttribute('data-pane-method') === method);
                });
            }

            function onlyDigits(v) {
                return (v || '').replace(/\D/g, '');
            }

            function maskCpf(input) {
                const v = onlyDigits(input.value).slice(0, 11);
                let out = v;

                if (v.length > 3) out = v.slice(0, 3) + '.' + v.slice(3);
                if (v.length > 6) out = out.slice(0, 7) + '.' + v.slice(6);
                if (v.length > 9) out = out.slice(0, 11) + '-' + v.slice(9);

                input.value = out;
            }

            function maskPhone(input) {
                const v = onlyDigits(input.value).slice(0, 11);
                let out = v;

                if (v.length >= 2) out = '(' + v.slice(0, 2) + ') ' + v.slice(2);
                if (v.length >= 7) out = out.slice(0, 10) + '-' + out.slice(10);

                input.value = out;
            }

            function maskCep(input) {
                const v = onlyDigits(input.value).slice(0, 8);
                let out = v;
                if (v.length > 5) out = v.slice(0, 5) + '-' + v.slice(5);
                input.value = out;
            }

            function markInvalid(input, invalid) {
                if (!input) return;
                input.classList.toggle('is-invalid', !!invalid);
            }

            async function viacepLookup(cep) {
                try {
                    const res = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
                    const data = await res.json();
                    if (data?.erro) return null;
                    return data;
                } catch (e) {
                    return null;
                }
            }

            function validateStep0() {
                if (!checkoutForm) return false;
                const name = checkoutForm.querySelector('input[name="buyer_name"]');
                const cpf = checkoutForm.querySelector('input[name="buyer_cpf"]');
                const phone = checkoutForm.querySelector('input[name="buyer_phone"]');
                const birth = checkoutForm.querySelector('input[name="buyer_birth"]');

                const okName = !!name?.value?.trim();
                const okCpf = onlyDigits(cpf?.value).length === 11;
                const okPhone = onlyDigits(phone?.value).length >= 10;
                const okBirth = !!birth?.value;

                markInvalid(name, !okName);
                markInvalid(cpf, !okCpf);
                markInvalid(phone, !okPhone);
                markInvalid(birth, !okBirth);

                return okName && okCpf && okPhone && okBirth;
            }

            function validateStep1() {
                if (!checkoutForm) return false;
                const cep = checkoutForm.querySelector('input[name="ship_cep"]');
                const street = checkoutForm.querySelector('input[name="ship_street"]');
                const number = checkoutForm.querySelector('input[name="ship_number"]');
                const district = checkoutForm.querySelector('input[name="ship_district"]');
                const city = checkoutForm.querySelector('input[name="ship_city"]');
                const uf = checkoutForm.querySelector('input[name="ship_uf"]');

                const okCep = onlyDigits(cep?.value).length === 8;
                const okStreet = !!street?.value?.trim();
                const okNumber = !!number?.value?.trim();
                const okDistrict = !!district?.value?.trim();
                const okCity = !!city?.value?.trim();
                const okUf = (uf?.value || '').trim().length === 2;

                markInvalid(cep, !okCep);
                markInvalid(street, !okStreet);
                markInvalid(number, !okNumber);
                markInvalid(district, !okDistrict);
                markInvalid(city, !okCity);
                markInvalid(uf, !okUf);

                return okCep && okStreet && okNumber && okDistrict && okCity && okUf;
            }

            function validateStep2() {
                if (!checkoutForm) return false;
                const method = getPaymentMethod();

                if (method === 'card') {
                    const n = checkoutForm.querySelector('input[name="card_number"]');
                    const exp = checkoutForm.querySelector('input[name="card_exp"]');
                    const cvv = checkoutForm.querySelector('input[name="card_cvv"]');
                    const name = checkoutForm.querySelector('input[name="card_name"]');

                    const okN = onlyDigits(n?.value).length >= 13;
                    const okExp = !!exp?.value?.trim();
                    const okCvv = onlyDigits(cvv?.value).length >= 3;
                    const okName = !!name?.value?.trim();

                    markInvalid(n, !okN);
                    markInvalid(exp, !okExp);
                    markInvalid(cvv, !okCvv);
                    markInvalid(name, !okName);

                    return okN && okExp && okCvv && okName;
                }

                if (method === 'boleto') {
                    const email = checkoutForm.querySelector('input[name="boleto_email"]');
                    const okEmail = !!email?.value?.trim();
                    markInvalid(email, !okEmail);
                    return okEmail;
                }

                // PIX: sem campos obrigatórios
                return true;
            }

            function bindCheckout() {
                if (!checkoutModalEl || !checkoutForm) return;

                // Reset quando abrir
                checkoutModalEl.addEventListener('show.bs.modal', () => {
                    setStep(0);
                    selectPayment(getPaymentMethod());
                    checkoutForm.querySelectorAll('.is-invalid').forEach((el) => el.classList.remove('is-invalid'));
                    if (btnFinish) {
                        btnFinish.disabled = false;
                        btnFinish.removeAttribute('data-loading');
                        btnFinish.innerHTML = 'Finalizar Pedido';
                    }
                });

                // ✅ Segurança de foco quando o modal fecha (evita warnings aria-hidden)
                checkoutModalEl.addEventListener('hidden.bs.modal', () => {
                    // Joga o foco para fora do modal
                    document.activeElement?.blur();
                    // Se quiser focar algo específico fora do modal, troque por um seletor real:
                    const fallback = document.querySelector('a, button, input, [tabindex]:not([tabindex="-1"])');
                    fallback?.focus?.();
                });

                // Máscaras
                const cpf = checkoutForm.querySelector('input[name="buyer_cpf"]');
                const phone = checkoutForm.querySelector('input[name="buyer_phone"]');
                const cep = checkoutForm.querySelector('input[name="ship_cep"]');
                const uf = checkoutForm.querySelector('input[name="ship_uf"]');

                cpf?.addEventListener('input', () => maskCpf(cpf));
                phone?.addEventListener('input', () => maskPhone(phone));
                cep?.addEventListener('input', () => maskCep(cep));
                uf?.addEventListener('input', () => {
                    uf.value = (uf.value || '').toUpperCase().replace(/[^A-Z]/g, '').slice(0, 2);
                });

                // Busca CEP (ViaCEP)
                cep?.addEventListener('blur', async () => {
                    const v = onlyDigits(cep.value);
                    if (v.length !== 8) return;
                    const data = await viacepLookup(v);
                    if (!data) return;

                    const street = checkoutForm.querySelector('input[name="ship_street"]');
                    const district = checkoutForm.querySelector('input[name="ship_district"]');
                    const city = checkoutForm.querySelector('input[name="ship_city"]');
                    const ufEl = checkoutForm.querySelector('input[name="ship_uf"]');

                    if (street && !street.value) street.value = data.logradouro || '';
                    if (district && !district.value) district.value = data.bairro || '';
                    if (city && !city.value) city.value = data.localidade || '';
                    if (ufEl && !ufEl.value) ufEl.value = (data.uf || '').toUpperCase();
                });

                // Navegação
                btnNext1?.addEventListener('click', () => {
                    if (validateStep0()) setStep(1);
                    else showFlash('danger', 'Confira os campos dos dados pessoais.');
                });

                btnBack2?.addEventListener('click', () => setStep(0));

                btnNext2?.addEventListener('click', () => {
                    if (validateStep1()) setStep(2);
                    else showFlash('danger', 'Confira os campos de entrega.');
                });

                btnBack3?.addEventListener('click', () => setStep(1));

                // Seleção pagamento
                paymentOptions.forEach((opt) => {
                    opt.addEventListener('click', () => selectPayment(opt.getAttribute('data-method')));
                });

                syncPaymentPanes();

                // ✅ Submit corrigido:
                // - só impede submit se estiver inválido
                // - quando válido, deixa o form enviar pro Laravel (route checkout.mercadopago)
                // - corrige warning aria-hidden: remove foco do botão antes do browser navegar
                checkoutForm.addEventListener('submit', (e) => {
                    // valida step 0/1 também (caso alguém force ir direto pro submit)
                    const ok0 = validateStep0();
                    const ok1 = validateStep1();
                    const ok2 = validateStep2();

                    if (!ok0) {
                        e.preventDefault();
                        setStep(0);
                        showFlash('danger', 'Confira os campos dos dados pessoais.');
                        return;
                    }

                    if (!ok1) {
                        e.preventDefault();
                        setStep(1);
                        showFlash('danger', 'Confira os campos de entrega.');
                        return;
                    }

                    if (!ok2) {
                        e.preventDefault();
                        setStep(2);
                        showFlash('danger', 'Confira os campos do pagamento.');
                        return;
                    }

                    // ✅ Importante: para Checkout Pro (redirect), você NÃO deve enviar dados do cartão.
                    // Se você vai apenas redirecionar para o Mercado Pago, limpe os campos sensíveis:
                    const method = getPaymentMethod();
                    if (method === 'card') {
                        const sensitive = ['card_number', 'card_exp', 'card_cvv', 'card_name'];
                        sensitive.forEach((name) => {
                            const el = checkoutForm.querySelector(`input[name="${name}"]`);
                            if (el) el.value = '';
                        });
                    }

                    // ✅ Remove foco do botão antes da navegação (evita aria-hidden warning)
                    document.activeElement?.blur();

                    // Evita duplo clique
                    if (btnFinish) {
                        if (btnFinish.getAttribute('data-loading') === '1') {
                            e.preventDefault();
                            return;
                        }
                        btnFinish.setAttribute('data-loading', '1');
                        btnFinish.disabled = true;
                        btnFinish.innerHTML = 'Redirecionando...';
                    }

                    // NÃO fecha o modal aqui. O redirect do backend vai acontecer.
                    // Se você fechar o modal manualmente aqui, volta o warning de aria-hidden com foco preso.
                });
            }

            bindCheckout();

            // Listener único (delegação)
            root.addEventListener('click', handleCardClick);
        });
    </script>

@endpush
