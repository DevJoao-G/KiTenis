@extends('layouts.app')

@section('title', $product->name)

@push('styles')
<style>
    .option-label { font-size: .9rem; }

    .color-options .btn,
    .size-options .btn { transition: all .15s ease; }

    .color-options .btn {
        border-radius: 999px;
        padding: .35rem .75rem;
    }

    .size-options .btn {
        width: 44px;
        height: 44px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: .6rem;
        font-weight: 600;
    }

    .btn-option-selected {
        border-color: #198754 !important;
        background-color: #198754 !important;
        color: #fff !important;
    }

    /* mantém o visual mesmo com hover/focus quando selecionado */
    .btn-option-selected:hover,
    .btn-option-selected:focus,
    .btn-option-selected:active,
    .btn-option-selected:focus-visible {
        border-color: #198754 !important;
        background-color: #198754 !important;
        color: #fff !important;
        box-shadow: none !important;
    }

    .btn-ghost-icon {
        width: 52px;
        min-width: 52px;
        height: 52px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: .75rem;
    }
</style>
@endpush

@section('content')
@php
    $colors = ['Preto', 'Azul Marinho', 'Vermelho'];
    $sizes  = ['38','39','40','41','42','43','44'];
@endphp

<div class="container py-5">

    <div class="mb-4">
        <a href="{{ route('products.index') }}" class="text-decoration-none text-muted">
            ← Voltar para Produtos
        </a>
    </div>

    <div class="row g-5 align-items-start">

        {{-- COLUNA IMAGEM --}}
        <div class="col-lg-6">
            <div class="border rounded-4 p-4 bg-light text-center">
                <img
                    src="{{ $product->image_url }}"
                    alt="{{ $product->name }}"
                    class="img-fluid rounded"
                    style="max-height: 420px; object-fit: contain;"
                >
            </div>

            <div class="d-flex gap-2 mt-3">
                <div class="border rounded p-2 bg-light">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" width="60">
                </div>
                <div class="border rounded p-2 bg-light opacity-50">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" width="60">
                </div>
            </div>
        </div>

        {{-- COLUNA INFORMAÇÕES --}}
        <div class="col-lg-6">

            <div class="text-uppercase text-muted small fw-semibold mb-1">
                {{ $product->brand ?? 'Marca' }}
            </div>

            <h1 class="fw-bold mb-2">{{ $product->name }}</h1>

            <div class="d-flex align-items-center mb-3">
                <div class="text-warning me-2">★★★★☆</div>
                <small class="text-muted">(4.7 • 891 avaliações)</small>
            </div>

            {{-- Preço --}}
            <div class="mb-3">
                @if ($product->is_promotion_active)
                    <div class="d-flex flex-column gap-1">
                        <small class="text-muted text-decoration-line-through">
                            R$ {{ number_format($product->price, 2, ',', '.') }}
                        </small>
                        <span class="fs-3 fw-bold text-success">
                            R$ {{ number_format($product->discounted_price, 2, ',', '.') }}
                        </span>
                    </div>
                @else
                    <span class="fs-3 fw-bold text-success">
                        R$ {{ number_format($product->price, 2, ',', '.') }}
                    </span>
                @endif

                <div class="text-muted small mt-1">
                    ou 10x de R$ {{ number_format(($product->is_promotion_active ? $product->discounted_price : $product->price) / 10, 2, ',', '.') }} sem juros
                </div>
            </div>

            <p class="text-muted mt-3">{{ $product->description }}</p>

            {{-- ERROS --}}
            @if ($errors->any())
                <div class="alert alert-danger mt-3">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div id="selectionError" class="mt-3"></div>

            {{-- FORM: ADICIONAR AO CARRINHO --}}
            <form method="POST" action="{{ route('cart.add') }}" class="mt-4" id="addToCartForm">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                {{-- Cor --}}
                <div class="mb-3">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div class="fw-semibold option-label">Cor:</div>
                        <div class="text-muted option-label">
                            <span id="colorLabel">Selecione</span>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2 color-options">
                        @foreach ($colors as $color)
                            <button type="button" class="btn btn-outline-secondary" data-color="{{ $color }}">
                                {{ $color }}
                            </button>
                        @endforeach
                    </div>

                    <input type="hidden" name="color" id="colorInput" value="{{ old('color') }}">
                </div>

                {{-- Tamanho --}}
                <div class="mb-3">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div class="fw-semibold option-label">Tamanho:</div>
                        <div class="text-muted option-label">
                            <span id="sizeLabel">Selecione</span>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2 size-options">
                        @foreach ($sizes as $size)
                            <button type="button" class="btn btn-outline-secondary" data-size="{{ $size }}">
                                {{ $size }}
                            </button>
                        @endforeach
                    </div>

                    <input type="hidden" name="size" id="sizeInput" value="{{ old('size') }}">
                </div>

                {{-- Quantidade --}}
                <div class="mt-4">
                    <label class="form-label fw-semibold">Quantidade</label>
                    <div class="input-group" style="max-width: 160px;">
                        <button class="btn btn-outline-secondary" type="button" id="qtyMinus">−</button>
                        <input
                            type="number"
                            class="form-control text-center"
                            name="qty"
                            id="qtyInput"
                            value="{{ old('qty', 1) }}"
                            min="1"
                            max="10"
                            required
                        >
                        <button class="btn btn-outline-secondary" type="button" id="qtyPlus">+</button>
                    </div>
                </div>

                {{-- Botões --}}
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-success btn-lg flex-grow-1" {{ $product->stock == 0 ? 'disabled' : '' }}>
                        <i class="bi bi-cart-plus me-2"></i>
                        Adicionar ao Carrinho
                    </button>

                    <button
                        type="button"
                        class="btn btn-outline-secondary btn-lg btn-ghost-icon btn-favoritar flex-shrink-0 {{ ($isFavorited ?? false) ? 'favoritado' : '' }}"
                        data-url="{{ route('favorites.toggle', $product) }}"
                        aria-label="Favoritar {{ $product->name }}"
                        title="Favoritar produto"
                    >
                        <i class="bi {{ ($isFavorited ?? false) ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                    </button>
                </div>

                <ul class="list-unstyled mt-4 text-muted">
                    <li class="d-flex align-items-center gap-2">
                        <i class="bi bi-truck"></i>
                        Frete grátis para compras acima de R$ 299
                    </li>
                    <li class="d-flex align-items-center gap-2 mt-2">
                        <i class="bi bi-arrow-repeat"></i>
                        30 dias para troca ou devolução
                    </li>
                    <li class="d-flex align-items-center gap-2 mt-2">
                        <i class="bi bi-shield-check"></i>
                        Produto 100% original
                    </li>
                </ul>
            </form>
        </div>
    </div>

    {{-- PRODUTOS RELACIONADOS --}}
    @if ($relatedProducts->count())
        <hr class="my-5">

        <h4 class="fw-bold mb-4">Produtos Relacionados</h4>

        <div class="row g-4">
            @foreach ($relatedProducts as $related)
                <div class="col-md-3">
                    <div class="card h-100 position-relative">

                        @if ($related->is_promotion_active)
                            <div class="position-absolute top-0 start-0 m-2" style="z-index: 10;">
                                <span class="badge bg-danger fw-semibold">{{ $related->discount_badge }}</span>
                            </div>
                        @endif

                        <img
                            src="{{ $related->image_url }}"
                            alt="{{ $related->name }}"
                            class="card-img-top"
                        >

                        <div class="card-body">
                            <div class="text-uppercase text-muted small fw-semibold mb-1">
                                {{ $related->brand ?? 'Marca' }}
                            </div>

                            <h6 class="card-title">{{ $related->name }}</h6>

                            @if ($related->is_promotion_active)
                                <small class="text-muted text-decoration-line-through">
                                    R$ {{ number_format($related->price, 2, ',', '.') }}
                                </small>
                                <p class="fw-bold text-success mb-0">
                                    R$ {{ number_format($related->discounted_price, 2, ',', '.') }}
                                </p>
                            @else
                                <p class="fw-bold text-success mb-0">
                                    R$ {{ number_format($related->price, 2, ',', '.') }}
                                </p>
                            @endif
                        </div>

                        <div class="card-footer bg-white border-0">
                            <a href="{{ route('products.show', $related) }}"
                               class="btn btn-sm btn-outline-success w-100">
                                Ver produto
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>

@guest
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1080;">
        <div id="loginRequiredToast" class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    Você precisa estar logado para continuar. Redirecionando...
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Fechar"></button>
            </div>
        </div>
    </div>
@endguest
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // OBS: isso é só pro carrinho/toast. Favorito confia no status do backend.
    const isGuest = {{ auth()->check() ? 'false' : 'true' }};
    const accessUrl = "{{ url('/access') }}";

    function showLoginRequiredToast() {
        const el = document.getElementById('loginRequiredToast');
        if (!el || typeof bootstrap === 'undefined' || !bootstrap.Toast) {
            window.location.assign(accessUrl);
            return;
        }
        const toast = bootstrap.Toast.getOrCreateInstance(el, { delay: 1600 });
        toast.show();
        setTimeout(() => window.location.assign(accessUrl), 900);
    }

    const colorButtons = document.querySelectorAll('.color-options button[data-color]');
    const sizeButtons  = document.querySelectorAll('.size-options button[data-size]');

    const colorInput = document.getElementById('colorInput');
    const sizeInput  = document.getElementById('sizeInput');

    const colorLabel = document.getElementById('colorLabel');
    const sizeLabel  = document.getElementById('sizeLabel');

    const errorBox = document.getElementById('selectionError');

    function setSelected(buttons, activeButton) {
        buttons.forEach(btn => btn.classList.remove('btn-option-selected'));
        if (activeButton) activeButton.classList.add('btn-option-selected');
    }

    function showError(message) {
        if (!errorBox) return;
        errorBox.innerHTML = `<div class="alert alert-danger py-2 mb-0">${message}</div>`;
    }

    function clearError() {
        if (!errorBox) return;
        errorBox.innerHTML = '';
    }

    // Estado inicial (old())
    if (colorInput && colorInput.value) {
        const btn = Array.from(colorButtons).find(b => b.getAttribute('data-color') === colorInput.value);
        if (btn) setSelected(colorButtons, btn);
        if (colorLabel) colorLabel.textContent = colorInput.value;
    }
    if (sizeInput && sizeInput.value) {
        const btn = Array.from(sizeButtons).find(b => b.getAttribute('data-size') === sizeInput.value);
        if (btn) setSelected(sizeButtons, btn);
        if (sizeLabel) sizeLabel.textContent = sizeInput.value;
    }

    colorButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const value = btn.getAttribute('data-color');
            colorInput.value = value;
            if (colorLabel) colorLabel.textContent = value;
            setSelected(colorButtons, btn);
            clearError();
        });
    });

    sizeButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const value = btn.getAttribute('data-size');
            sizeInput.value = value;
            if (sizeLabel) sizeLabel.textContent = value;
            setSelected(sizeButtons, btn);
            clearError();
        });
    });

    // Quantidade
    const qtyMinus = document.getElementById('qtyMinus');
    const qtyPlus  = document.getElementById('qtyPlus');
    const qtyInput = document.getElementById('qtyInput');

    function clamp(n, min, max) {
        return Math.max(min, Math.min(max, n));
    }

    if (qtyMinus && qtyPlus && qtyInput) {
        qtyMinus.addEventListener('click', () => {
            const v = parseInt(qtyInput.value || '1', 10);
            qtyInput.value = clamp(v - 1, 1, 10);
        });
        qtyPlus.addEventListener('click', () => {
            const v = parseInt(qtyInput.value || '1', 10);
            qtyInput.value = clamp(v + 1, 1, 10);
        });
    }

    // Validação antes de enviar
    const form = document.getElementById('addToCartForm');
    if (form) {
        form.addEventListener('submit', (e) => {
            if (isGuest) {
                e.preventDefault();
                clearError();
                showLoginRequiredToast();
                return;
            }
            const c = (colorInput?.value || '').trim();
            const s = (sizeInput?.value || '').trim();

            if (!c || !s) {
                e.preventDefault();
                showError('Selecione <strong>cor</strong> e <strong>tamanho</strong> para adicionar ao carrinho.');
            }
        });
    }

    // Favoritar (toggle)
    const favBtn = document.querySelector('.btn-favoritar');
    if (favBtn) {
        favBtn.addEventListener('click', async () => {
            const url = favBtn.getAttribute('data-url');
            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            try {
                const res = await fetch(url, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrf || '',
                        'Accept': 'application/json',
                    },
                });

                if (res.status === 401 || res.status === 419) {
                    showLoginRequiredToast();
                    return;
                }

                const data = await res.json();
                if (!data?.ok) return;

                const isOn = !!data.favorited;

                favBtn.classList.toggle('favoritado', isOn);

                const icon = favBtn.querySelector('i.bi');
                if (icon) {
                    icon.classList.toggle('bi-heart-fill', isOn);
                    icon.classList.toggle('bi-heart', !isOn);
                }
            } catch (err) {
                console.error('Erro ao favoritar:', err);
            }
        });
    }
});
</script>
@endpush
