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

                        <div class="card border-0 shadow-sm mb-3 cart-item" data-key="{{ $item['key'] }}"
                            data-update-url="{{ route('cart.update', $item['key']) }}"
                            data-remove-url="{{ route('cart.remove', $item['key']) }}">
                            <div class="card-body">
                                <div class="d-flex gap-3 align-items-center">

                                    <div style="width: 92px; min-width: 92px;">
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="img-fluid rounded"
                                            style="width: 92px; height: 92px; object-fit: cover;">
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
                                                <button class="btn btn-outline-secondary btn-dec" type="button"
                                                    aria-label="Diminuir">−</button>
                                                <input type="text" class="form-control text-center qty-value"
                                                    value="{{ $item['qty'] }}" readonly>
                                                <button class="btn btn-outline-secondary btn-inc" type="button"
                                                    aria-label="Aumentar">+</button>
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

                            <button class="btn btn-success w-100 fw-semibold mb-2" type="button" data-bs-toggle="modal"
                                data-bs-target="#checkoutModal">
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
                                            <label class="form-label fw-semibold">Nome completo <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="buyer_name" placeholder="" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">CPF <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="buyer_cpf"
                                                placeholder="Somente números" inputmode="numeric" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Celular <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="buyer_phone"
                                                placeholder="DDD + número" inputmode="numeric" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Data de Nascimento <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" class="form-control" name="buyer_birth" required>
                                        </div>
                                    </div>

                                    <div class="checkout-actions">
                                        <div></div>
                                        <button type="button" class="btn btn-success" id="btnNext1">
                                            Próximo <i class="bi bi-arrow-right ms-1"></i>
                                        </button>
                                    </div>
                                </div>

                                {{-- ETAPA 2 --}}
                                <div class="checkout-step-pane" data-pane="1">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold">CEP <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="ship_cep" placeholder="00000000"
                                                inputmode="numeric" required>
                                            <div class="form-text">Ao preencher o CEP, tentaremos completar automaticamente.
                                            </div>
                                        </div>

                                        <div class="col-md-8">
                                            <label class="form-label fw-semibold">Rua <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="ship_street" placeholder="" required>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold">Número <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="ship_number" required>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold">Complemento (apto)</label>
                                            <input type="text" class="form-control" name="ship_complement"
                                                placeholder="Ex: Apto 12, Bloco B">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold">Bairro <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="ship_district" required>
                                        </div>

                                        <div class="col-md-8">
                                            <label class="form-label fw-semibold">Cidade <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="ship_city" required>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold">Estado (UF) <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="ship_uf" maxlength="2"
                                                placeholder="SP" required>
                                        </div>
                                    </div>

                                    <div class="checkout-actions">
                                        <button type="button" class="btn btn-outline-light" id="btnBack2">
                                            Voltar
                                        </button>
                                        <button type="button" class="btn btn-success" id="btnNext2">
                                            Próximo <i class="bi bi-arrow-right ms-1"></i>
                                        </button>
                                    </div>
                                </div>

                                {{-- ETAPA 3 --}}
                                <div class="checkout-step-pane" data-pane="2">
                                    <label class="form-label fw-semibold">Forma de Pagamento</label>

                                    <div class="payment-options" id="paymentOptions">
                                        <div class="payment-option is-selected" data-method="card">
                                            <div class="form-check m-0 d-flex align-items-start gap-2">
                                                <input class="form-check-input" type="radio" name="payment_method" value="card"
                                                    checked>
                                                <div class="payment-label">
                                                    <span class="payment-icon"><i class="bi bi-credit-card"></i></span>
                                                    <span>Cartão de Crédito</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="payment-option" data-method="boleto">
                                            <div class="form-check m-0 d-flex align-items-start gap-2">
                                                <input class="form-check-input" type="radio" name="payment_method"
                                                    value="boleto">
                                                <div class="payment-label">
                                                    <span class="payment-icon"><i class="bi bi-receipt"></i></span>
                                                    <span>Boleto Bancário</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="payment-option" data-method="pix">
                                            <div class="form-check m-0 d-flex align-items-start gap-2">
                                                <input class="form-check-input" type="radio" name="payment_method" value="pix">
                                                <div class="payment-label">
                                                    <span class="payment-icon"><i class="bi bi-qr-code"></i></span>
                                                    <span>PIX</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-3" id="paymentPaneCard">
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label class="form-label fw-semibold">Número do Cartão <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="card_number"
                                                    placeholder="0000 0000 0000 0000" inputmode="numeric">
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Validade <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="card_exp" placeholder="MM/AA"
                                                    inputmode="numeric">
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">CVV <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="card_cvv" placeholder="123"
                                                    inputmode="numeric" maxlength="4">
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label fw-semibold">Nome no Cartão <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="card_name"
                                                    placeholder="NOME COMO NO CARTÃO">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-3 d-none" id="paymentPaneBoleto">
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label class="form-label fw-semibold">E-mail para envio do boleto <span
                                                        class="text-danger">*</span></label>
                                                <input type="email" class="form-control" name="boleto_email"
                                                    placeholder="seuemail@exemplo.com">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-3 d-none" id="paymentPanePix">
                                        <div class="alert alert-success mb-0">
                                            Ao finalizar, vamos gerar o QR Code / Copia e Cola do PIX para pagamento.
                                        </div>
                                    </div>

                                    <div class="checkout-actions">
                                        <button type="button" class="btn btn-outline-light" id="btnBack3">
                                            Voltar
                                        </button>
                                        <button type="submit" class="btn btn-success" id="btnFinish">
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

                let badge = cartLink.querySelector('span.badge');

                if (!count || count <= 0) {
                    if (badge) badge.remove();
                    return;
                }

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

                        const qtyEl = card.querySelector('.qty-value');
                        if (qtyEl && typeof data.qty !== 'undefined') qtyEl.value = data.qty;

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

            // =============================
            // Checkout (Modal 3 Etapas)
            // =============================
            const checkoutModalEl = document.getElementById('checkoutModal');
            const checkoutForm = document.getElementById('checkoutForm');
            const stepEls = Array.from(document.querySelectorAll('#checkoutSteps .checkout-step'));
            const paneEls = Array.from(document.querySelectorAll('.checkout-step-pane'));

            const btnNext1 = document.getElementById('btnNext1');
            const btnNext2 = document.getElementById('btnNext2');
            const btnBack2 = document.getElementById('btnBack2');
            const btnBack3 = document.getElementById('btnBack3');
            const btnFinish = document.getElementById('btnFinish');

            const paymentOptions = Array.from(document.querySelectorAll('#paymentOptions .payment-option'));
            const paneCard = document.getElementById('paymentPaneCard');
            const paneBoleto = document.getElementById('paymentPaneBoleto');
            const panePix = document.getElementById('paymentPanePix');

            let currentStep = 0;

            function onlyDigits(v) {
                return (v || '').replace(/\D+/g, '');
            }

            function maskCpf(input) {
                const v = onlyDigits(input.value).slice(0, 11);
                const p1 = v.slice(0, 3);
                const p2 = v.slice(3, 6);
                const p3 = v.slice(6, 9);
                const p4 = v.slice(9, 11);
                let out = p1;
                if (p2) out += '.' + p2;
                if (p3) out += '.' + p3;
                if (p4) out += '-' + p4;
                input.value = out;
            }

            function maskPhone(input) {
                const v = onlyDigits(input.value).slice(0, 11);
                const ddd = v.slice(0, 2);
                const p1 = v.slice(2, 7);
                const p2 = v.slice(7, 11);
                let out = '';
                if (ddd) out += '(' + ddd + ') ';
                out += p1;
                if (p2) out += '-' + p2;
                input.value = out.trim();
            }

            function maskCep(input) {
                input.value = onlyDigits(input.value).slice(0, 8);
            }

            async function viacepLookup(cep) {
                try {
                    const res = await fetch(`https://viacep.com.br/ws/${cep}/json/`, { headers: { 'Accept': 'application/json' } });
                    const data = await res.json();
                    if (!res.ok || data?.erro) return null;
                    return data;
                } catch {
                    return null;
                }
            }

            function setStep(index) {
                currentStep = index;

                paneEls.forEach((pane) => {
                    pane.classList.toggle('is-visible', Number(pane.getAttribute('data-pane')) === index);
                });

                stepEls.forEach((stepEl) => {
                    const s = Number(stepEl.getAttribute('data-step'));
                    stepEl.classList.toggle('is-active', s === index);
                });
            }

            function getPaymentMethod() {
                const checked = checkoutForm?.querySelector('input[name="payment_method"]:checked');
                return checked?.value || 'card';
            }

            function syncPaymentPanes() {
                const method = getPaymentMethod();
                if (paneCard) paneCard.classList.toggle('d-none', method !== 'card');
                if (paneBoleto) paneBoleto.classList.toggle('d-none', method !== 'boleto');
                if (panePix) panePix.classList.toggle('d-none', method !== 'pix');
            }

            function selectPayment(method) {
                paymentOptions.forEach((opt) => {
                    const isThis = opt.getAttribute('data-method') === method;
                    opt.classList.toggle('is-selected', isThis);
                    const radio = opt.querySelector('input[type="radio"]');
                    if (radio && isThis) radio.checked = true;
                });
                syncPaymentPanes();
            }

            function markInvalid(el, invalid) {
                if (!el) return;
                el.classList.toggle('is-invalid', !!invalid);
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

                syncPaymentPanes();
            }

            bindCheckout();

            // Listener único (delegação)
            root.addEventListener('click', handleCardClick);
        });
    </script>

@endpush