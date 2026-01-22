@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h1 class="mb-4 fw-bold">
        Produtos
    </h1>

    @if($products->count())
        <div class="row g-4">
            @foreach($products as $product)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card h-100 shadow-sm">

                        {{-- Imagem --}}
                        <img 
                            src="{{ $product->image ?? 'https://via.placeholder.com/300x300?text=KiTenis' }}" 
                            class="card-img-top"
                            alt="{{ $product->name }}"
                        >

                        <div class="card-body d-flex flex-column">

                            {{-- Categoria --}}
                            <span class="badge bg-secondary mb-2 text-capitalize">
                                {{ $product->category }}
                            </span>

                            {{-- Nome --}}
                            <h6 class="card-title fw-semibold">
                                {{ $product->name }}
                            </h6>

                            {{-- Preço --}}
                            <p class="fw-bold text-success fs-5 mb-3">
                                R$ {{ number_format($product->price, 2, ',', '.') }}
                            </p>

                            {{-- Botão --}}
                            <a 
                                href="{{ route('products.show', $product->id) }}" 
                                class="btn btn-outline-success mt-auto w-100"
                            >
                                Ver detalhes
                            </a>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Paginação --}}
        <div class="mt-4">
            {{ $products->links() }}
        </div>
    @else
        <div class="alert alert-warning">
            Nenhum produto encontrado.
        </div>
    @endif

</div>
@endsection
