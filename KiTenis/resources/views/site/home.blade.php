@extends('layouts.app')

@section('title', 'Início')

@section('content')

   {{-- OFERTAS (apenas promoções ativas; se não tiver, não mostra) --}}
@if(isset($ofertas) && $ofertas->count() > 0)
    <section class="bg-light py-5" data-aos="fade-up" data-aos-duration="1000">
        <div class="container">
            <div class="text-center mb-4">
                <h2 class="fw-bold fs-1">Ofertas da Semana</h2>
                <p class="text-secondary">Os melhores tênis em promoção agora!</p>
            </div>

            @php
                $count = max(1, $ofertas->count());
                $duration = max(24, $count * 3); // segundos
            @endphp

            <div class="ofertas-marquee" style="--duration: {{ $duration }}s;">
                <div class="ofertas-track">

                    {{-- Set 1 --}}
                    @foreach ($ofertas as $produto)
                        <div class="oferta-slide">
                            <div class="card h-100 shadow-sm border-0 position-relative oferta-card">

                                <div class="position-absolute top-0 start-0 m-2" style="z-index: 10;">
                                    <span class="badge bg-danger fw-semibold">
                                        {{ $produto->discount_badge ?? '-0%' }}
                                    </span>
                                </div>

                                @php
                                    $isFavorited = in_array($produto->id, $favoriteIds ?? [], true);
                                @endphp

                                <button
                                    class="btn-favoritar position-absolute top-0 end-0 m-2 bg-white rounded-circle border-0 shadow-sm {{ $isFavorited ? 'favoritado' : '' }}"
                                    data-produto-id="{{ $produto->id }}"
                                    data-url="{{ route('favorites.toggle', $produto) }}"
                                    style="width: 38px; height: 38px; z-index: 10;"
                                    aria-label="Favoritar {{ $produto->name }}"
                                    title="Favoritar produto"
                                >
                                    <i class="bi {{ $isFavorited ? 'bi-heart-fill' : 'bi-heart' }} fs-5"></i>
                                </button>

                                <div class="d-flex align-items-center justify-content-center oferta-img">
                                    <img
                                        src="{{ $produto->image_url }}"
                                        class="img-fluid p-3"
                                        alt="{{ $produto->name }}"
                                        loading="lazy"
                                        style="max-height: 190px; object-fit: contain;"
                                    >
                                </div>

                                <div class="card-body d-flex flex-column pt-2">
                                    <div class="mb-1" role="img" aria-label="Avaliação 5 estrelas">
                                        <span class="text-warning small">★★★★★</span>
                                    </div>

                                    <h6 class="fw-semibold mb-2 oferta-title">
                                        {{ $produto->name }}
                                    </h6>

                                    <div class="mb-3">
                                        <small class="text-muted text-decoration-line-through">
                                            R$ {{ number_format($produto->price, 2, ',', '.') }}
                                        </small>

                                        <div class="h5 fw-bold text-success mb-0">
                                            R$ {{ number_format($produto->discounted_price, 2, ',', '.') }}
                                        </div>

                                        <small class="text-muted">
                                            4x de R$ {{ number_format($produto->discounted_price / 4, 2, ',', '.') }}
                                        </small>
                                    </div>

                                    <div class="mt-auto">
                                        <a href="{{ route('products.show', $produto->id) }}"
                                           class="btn btn-success w-100 fw-semibold">
                                            Ver detalhes
                                        </a>

                                        @if ($produto->stock > 0)
                                            <small class="text-success d-flex align-items-center justify-content-center gap-1 mt-2">
                                                <i class="bi bi-check-circle-fill"></i>
                                                <span>Frete grátis</span>
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    {{-- Set 2 (duplicado para loop perfeito) --}}
                    @foreach ($ofertas as $produto)
                        <div class="oferta-slide" aria-hidden="true">
                            <div class="card h-100 shadow-sm border-0 position-relative oferta-card">

                                <div class="position-absolute top-0 start-0 m-2" style="z-index: 10;">
                                    <span class="badge bg-danger fw-semibold">
                                        {{ $produto->discount_badge ?? '-0%' }}
                                    </span>
                                </div>

                                @php
                                    $isFavorited = in_array($produto->id, $favoriteIds ?? [], true);
                                @endphp

                                <button
                                    class="btn-favoritar position-absolute top-0 end-0 m-2 bg-white rounded-circle border-0 shadow-sm {{ $isFavorited ? 'favoritado' : '' }}"
                                    data-produto-id="{{ $produto->id }}"
                                    data-url="{{ route('favorites.toggle', $produto) }}"
                                    style="width: 38px; height: 38px; z-index: 10;"
                                    aria-label="Favoritar {{ $produto->name }}"
                                    title="Favoritar produto"
                                >
                                    <i class="bi {{ $isFavorited ? 'bi-heart-fill' : 'bi-heart' }} fs-5"></i>
                                </button>

                                <div class="d-flex align-items-center justify-content-center oferta-img">
                                    <img
                                        src="{{ $produto->image_url }}"
                                        class="img-fluid p-3"
                                        alt="{{ $produto->name }}"
                                        loading="lazy"
                                        style="max-height: 190px; object-fit: contain;"
                                    >
                                </div>

                                <div class="card-body d-flex flex-column pt-2">
                                    <div class="mb-1" role="img" aria-label="Avaliação 5 estrelas">
                                        <span class="text-warning small">★★★★★</span>
                                    </div>

                                    <h6 class="fw-semibold mb-2 oferta-title">
                                        {{ $produto->name }}
                                    </h6>

                                    <div class="mb-3">
                                        <small class="text-muted text-decoration-line-through">
                                            R$ {{ number_format($produto->price, 2, ',', '.') }}
                                        </small>

                                        <div class="h5 fw-bold text-success mb-0">
                                            R$ {{ number_format($produto->discounted_price, 2, ',', '.') }}
                                        </div>

                                        <small class="text-muted">
                                            4x de R$ {{ number_format($produto->discounted_price / 4, 2, ',', '.') }}
                                        </small>
                                    </div>

                                    <div class="mt-auto">
                                        <a href="{{ route('products.show', $produto->id) }}"
                                           class="btn btn-success w-100 fw-semibold">
                                            Ver detalhes
                                        </a>

                                        @if ($produto->stock > 0)
                                            <small class="text-success d-flex align-items-center justify-content-center gap-1 mt-2">
                                                <i class="bi bi-check-circle-fill"></i>
                                                <span>Frete grátis</span>
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </section>
@endif



    {{-- NAVEGUE POR MARCAS --}}
    <section class="py-5 bg-white" data-aos="fade-up" data-aos-duration="1000">
        <div class="container">
            <h3 class="fw-bold mb-4">Navegue por Marcas</h3>

            <div class="row g-4">
                @forelse($marcas as $marca)
                    <div class="col-6 col-md-2">
                        <a href="{{ route('products.index', ['search' => $marca->name]) }}"
                            class="card h-100 text-decoration-none border hover-shadow" data-aos="flip-up"
                            data-aos-delay="100" data-aos-duration="1000">
                            <div class="card-body d-flex align-items-center justify-content-center p-4">
                                @if (!empty($marca->logo_path))
                                    <img src="{{ asset('storage/' . $marca->logo_path) }}" alt="{{ $marca->name }}"
                                        class="img-fluid" style="max-height: 60px; filter: grayscale(100%);"
                                        onmouseover="this.style.filter='grayscale(0%)'"
                                        onmouseout="this.style.filter='grayscale(100%)'" loading="lazy">
                                @else
                                    <span class="fw-semibold text-dark">{{ $marca->name }}</span>
                                @endif
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-muted mb-0">Nenhuma marca disponível no momento.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- NEWSLETTER --}}
    <section class="py-5 bg-white">
        <div class="container">
            <div class="card shadow border-0" data-aos="zoom-in">
                <div class="card-body p-5 text-center">
                    <h3 class="fw-bold mb-3">Receba descontos exclusivos</h3>
                    <p class="text-muted mb-4">
                        Cadastre-se para receber ofertas por e-mail. Veja nossa
                        <a href="#" class="text-success text-decoration-none">Política de Privacidade</a>.
                    </p>
                    <form method="POST" action="{{ route('newsletter.subscribe') }}"
                        class="row g-3 justify-content-center">
                        @csrf
                        <div class="col-md-4">
                            <input type="text" name="name"
                                class="form-control form-control-lg @error('name') is-invalid @enderror"
                                placeholder="Seu nome" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <input type="email" name="email"
                                class="form-control form-control-lg @error('email') is-invalid @enderror"
                                placeholder="Seu e-mail" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-success btn-lg w-100">Cadastrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

@endsection




@push('scripts')
    <!-- AOS Library -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>

@endpush
