@extends('layouts.app')

@section('title', $pageTitle ?? 'Produtos')

@section('content')
    <div class="container py-4">

        <h1 class="mt-4 mb-4 fw-bold">
            {{ $pageTitle ?? 'Produtos' }}
        </h1>

        @if ($products->count())
            <div class="row g-4">
                @foreach ($products as $product)
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="card h-100 shadow-sm position-relative">

                            @if ($product->is_promotion_active)
                                <div class="position-absolute top-0 start-0 m-2" style="z-index: 10;">
                                    <span class="badge bg-danger fw-semibold">{{ $product->discount_badge }}</span>
                                </div>
                            @endif

                            <img
                                src="{{ $product->image_url }}"
                                alt="{{ $product->name }}"
                                class="card-img-top"
                            >

                            <div class="card-body d-flex flex-column">
                                <span class="badge bg-secondary mb-2 text-capitalize">
                                    {{ $product->category }}
                                </span>

                                <h6 class="card-title fw-semibold">
                                    {{ $product->name }}
                                </h6>

                                @if ($product->is_promotion_active)
                                    <div class="mb-2">
                                        <small class="text-muted text-decoration-line-through">
                                            R$ {{ number_format($product->price, 2, ',', '.') }}
                                        </small>

                                        <p class="fw-bold text-success fs-5 mb-0">
                                            R$ {{ number_format($product->discounted_price, 2, ',', '.') }}
                                        </p>

                                        <small class="text-muted">
                                            4x de R$ {{ number_format($product->discounted_price / 4, 2, ',', '.') }}
                                        </small>
                                    </div>
                                @else
                                    <p class="fw-bold text-success fs-5 mb-3">
                                        R$ {{ number_format($product->price, 2, ',', '.') }}
                                    </p>
                                @endif

                                <a href="{{ route('products.show', $product->id) }}"
                                   class="btn btn-outline-success mt-auto w-100">
                                    Ver detalhes
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <x-pagination :paginator="$products" />
        @else
            <div class="alert alert-warning">Nenhum produto encontrado.</div>
        @endif

    </div>
@endsection
