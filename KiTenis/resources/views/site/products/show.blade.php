@extends('layouts.app')

@section('content')
<div class="container py-5">

    {{-- Breadcrumb / Voltar --}}
    <div class="mb-4">
        <a href="{{ route('products.index') }}" class="text-decoration-none text-muted">
            ← Voltar para produtos
        </a>
    </div>

    <div class="row g-5 align-items-start">

        {{-- COLUNA IMAGEM --}}
        <div class="col-lg-6">
            <div class="border rounded-4 p-4 bg-light text-center">
                <img
                    src="{{ $product->image_url }}" alt="{{ $product->name }}"
                    alt="{{ $product->name }}"
                    class="img-fluid rounded"
                    style="max-height: 420px; object-fit: contain;"
                >
            </div>

            {{-- Thumbnails (estrutura futura) --}}
            <div class="d-flex gap-2 mt-3">
                <div class="border rounded p-2 bg-light">
                    <img src="{{ asset('images/placeholder-product.png') }}" width="60" alt="">
                </div>
                <div class="border rounded p-2 bg-light opacity-50">
                    <img src="{{ asset('images/placeholder-product.png') }}" width="60" alt="">
                </div>
            </div>
        </div>

        {{-- COLUNA INFORMAÇÕES --}}
        <div class="col-lg-6">

            {{-- Categoria --}}
            <span class="badge bg-secondary text-capitalize mb-2">
                {{ $product->category }}
            </span>

            {{-- Nome --}}
            <h1 class="fw-bold mb-3">
                {{ $product->name }}
            </h1>

            {{-- Avaliação fake (UI) --}}
            <div class="d-flex align-items-center mb-3">
                <div class="text-warning me-2">
                    ★★★★☆
                </div>
                <small class="text-muted">(4.6 • 312 avaliações)</small>
            </div>

            {{-- Preço --}}
            <div class="mb-4">
                <span class="fs-3 fw-bold text-success">
                    R$ {{ number_format($product->price, 2, ',', '.') }}
                </span>
            </div>

            {{-- Estoque --}}
            @if($product->stock > 0)
                <p class="text-success fw-semibold">
                    ✔ Em estoque ({{ $product->stock }} unidades)
                </p>
            @else
                <p class="text-danger fw-semibold">
                    ✖ Produto indisponível
                </p>
            @endif

            {{-- Descrição --}}
            <p class="text-muted mt-4">
                {{ $product->description }}
            </p>

            {{-- Quantidade --}}
            <div class="mt-4">
                <label class="form-label fw-semibold">Quantidade</label>
                <div class="input-group" style="max-width: 140px;">
                    <button class="btn btn-outline-secondary" type="button">−</button>
                    <input type="text" class="form-control text-center" value="1" readonly>
                    <button class="btn btn-outline-secondary" type="button">+</button>
                </div>
            </div>

            {{-- Ações --}}
            <div class="d-grid gap-2 mt-4">
                <button
                    class="btn btn-success btn-lg"
                    {{ $product->stock == 0 ? 'disabled' : '' }}
                >
                    <i class="bi bi-cart-plus me-2"></i>
                    Adicionar ao carrinho
                </button>

                <button class="btn btn-outline-secondary">
                    ♡ Favoritar
                </button>
            </div>

            {{-- Benefícios --}}
            <ul class="list-unstyled mt-4 text-muted">
                <li>🚚 Frete grátis para compras acima de R$ 299</li>
                <li>🔄 30 dias para troca ou devolução</li>
                <li>🛡 Produto 100% original</li>
            </ul>

        </div>
    </div>

    {{-- PRODUTOS RELACIONADOS --}}
    @if($relatedProducts->count())
        <hr class="my-5">

        <h4 class="fw-bold mb-4">Produtos relacionados</h4>

        <div class="row g-4">
            @foreach($relatedProducts as $related)
                <div class="col-md-3">
                    <div class="card h-100">
                        <img
                            src="{{ asset('images/placeholder-product.png') }}"
                            class="card-img-top"
                            alt="{{ $related->name }}"
                        >
                        <div class="card-body">
                            <h6 class="card-title">
                                {{ $related->name }}
                            </h6>
                            <p class="fw-bold text-success mb-0">
                                R$ {{ number_format($related->price, 2, ',', '.') }}
                            </p>
                        </div>
                        <div class="card-footer bg-white border-0">
                            <a
                                href="{{ route('products.show', $related) }}"
                                class="btn btn-sm btn-outline-success w-100"
                            >
                                Ver produto
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection
