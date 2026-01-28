@extends('layouts.app')

@section('title', 'Carrinho')

@section('content')
    <div class="container py-4">

        <div class="d-flex align-items-center justify-content-between mt-4 mb-4">
            <h1 class="fw-bold mb-0">Carrinho</h1>
            <span class="badge bg-success">
                {{ $favorites->total() }} item(ns)
            </span>
        </div>

        @if ($favorites->count())
            <div class="row g-4">
                @foreach ($favorites as $product)
                    <div class="col-12 col-md-6 col-lg-4">
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

                                <div class="mt-auto d-grid gap-2">
                                    <a href="{{ route('products.show', $product->id) }}"
                                       class="btn btn-outline-success">
                                        Ver detalhes
                                    </a>

                                    <form method="POST" action="{{ route('cart.remove', $product->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger w-100">
                                            Remover
                                        </button>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>

            <x-pagination :paginator="$favorites" />
        @else
            <div class="alert alert-warning">
                Você ainda não tem itens no carrinho (favoritos).
                <a class="alert-link" href="{{ route('products.index') }}">Ver produtos</a>
            </div>
        @endif

    </div>
@endsection
