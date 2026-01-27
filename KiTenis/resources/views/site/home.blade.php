@extends('layouts.app')

@section('title', 'Início')

@section('content')

    {{-- OFERTAS (MARQUEE INFINITO – alinhado em linha, sem sobrepor) --}}
    <section class="bg-light py-5" data-aos="fade-up" data-aos-duration="1000">
        <div class="container">
            <div class="text-center mb-4">
                <h2 class="fw-bold fs-1">Ofertas da Semana</h2>
                <p class="text-secondary">Os melhores tênis com até 42% de desconto!</p>
            </div>

            @php
                $count = max(1, $ofertas->count());
                // 10 itens -> ~30s fica suave
                $duration = max(24, $count * 3); // segundos
            @endphp

            <div class="ofertas-marquee" style="--duration: {{ $duration }}s;">
                <div class="ofertas-track">

                    {{-- Set 1 --}}
                    @foreach ($ofertas as $produto)
                        <div class="oferta-slide">
                            <div class="card h-100 shadow-sm border-0 position-relative oferta-card">
                                @php $desconto = round((1 - $produto->price / 600) * 100); @endphp

                                <div class="position-absolute top-0 start-0 m-2" style="z-index: 10;">
                                    <span class="badge bg-danger fw-semibold">-{{ $desconto }}%</span>
                                </div>

                                <button
                                    class="btn-favoritar position-absolute top-0 end-0 m-2 bg-white rounded-circle border-0 shadow-sm"
                                    data-produto-id="{{ $produto->id }}" style="width: 38px; height: 38px; z-index: 10;"
                                    aria-label="Favoritar {{ $produto->name }}" title="Favoritar produto">
                                    <i class="bi bi-heart fs-5"></i>
                                </button>

                                <div class="d-flex align-items-center justify-content-center oferta-img">
                                    <img src="{{ $produto->image_url }}" class="img-fluid p-3" alt="{{ $produto->name }}"
                                        loading="lazy" style="max-height: 190px; object-fit: contain;">
                                </div>

                                <div class="card-body d-flex flex-column pt-2">
                                    <div class="mb-1" role="img" aria-label="Avaliação 5 estrelas">
                                        <span class="text-warning small">★★★★★</span>
                                    </div>

                                    <h6 class="fw-semibold mb-2 oferta-title">
                                        {{ $produto->name }}
                                    </h6>

                                    <div class="mb-3">
                                        <small class="text-muted text-decoration-line-through">R$ 599,90</small>
                                        <div class="h5 fw-bold text-success mb-0">
                                            R$ {{ number_format($produto->price, 2, ',', '.') }}
                                        </div>
                                        <small class="text-muted">
                                            4x de R$ {{ number_format($produto->price / 4, 2, ',', '.') }}
                                        </small>
                                    </div>

                                    <div class="mt-auto">
                                        <a href="{{ route('products.show', $produto->id) }}"
                                            class="btn btn-success w-100 fw-semibold">
                                            Ver detalhes
                                        </a>

                                        @if ($produto->stock > 0)
                                            <small
                                                class="text-success d-flex align-items-center justify-content-center gap-1 mt-2">
                                                <i class="bi bi-check-circle-fill"></i>
                                                <span>Frete grátis</span>
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    {{-- Set 2 (duplicado para loop perfeito “tela a tela”) --}}
                    @foreach ($ofertas as $produto)
                        <div class="oferta-slide" aria-hidden="true">
                            <div class="card h-100 shadow-sm border-0 position-relative oferta-card">
                                @php $desconto = round((1 - $produto->price / 600) * 100); @endphp

                                <div class="position-absolute top-0 start-0 m-2" style="z-index: 10;">
                                    <span class="badge bg-danger fw-semibold">-{{ $desconto }}%</span>
                                </div>

                                <button
                                    class="btn-favoritar position-absolute top-0 end-0 m-2 bg-white rounded-circle border-0 shadow-sm"
                                    data-produto-id="{{ $produto->id }}" style="width: 38px; height: 38px; z-index: 10;"
                                    aria-label="Favoritar {{ $produto->name }}" title="Favoritar produto">
                                    <i class="bi bi-heart fs-5"></i>
                                </button>

                                <div class="d-flex align-items-center justify-content-center oferta-img">
                                    <img src="{{ $produto->image_url }}" class="img-fluid p-3" alt="{{ $produto->name }}"
                                        loading="lazy" style="max-height: 190px; object-fit: contain;">
                                </div>

                                <div class="card-body d-flex flex-column pt-2">
                                    <div class="mb-1" role="img" aria-label="Avaliação 5 estrelas">
                                        <span class="text-warning small">★★★★★</span>
                                    </div>

                                    <h6 class="fw-semibold mb-2 oferta-title">
                                        {{ $produto->name }}
                                    </h6>

                                    <div class="mb-3">
                                        <small class="text-muted text-decoration-line-through">R$ 599,90</small>
                                        <div class="h5 fw-bold text-success mb-0">
                                            R$ {{ number_format($produto->price, 2, ',', '.') }}
                                        </div>
                                        <small class="text-muted">
                                            4x de R$ {{ number_format($produto->price / 4, 2, ',', '.') }}
                                        </small>
                                    </div>

                                    <div class="mt-auto">
                                        <a href="{{ route('products.show', $produto->id) }}"
                                            class="btn btn-success w-100 fw-semibold">
                                            Ver detalhes
                                        </a>

                                        @if ($produto->stock > 0)
                                            <small
                                                class="text-success d-flex align-items-center justify-content-center gap-1 mt-2">
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

            <div class="text-center mt-4">
                <a href="{{ route('products.index', ['ofertas' => true]) }}" class="btn btn-success btn-lg px-5">
                    Ver todas as ofertas
                    <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </section>


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

@push('styles')
    <style>
        /* ====== Marcas (cards) ====== */
        .hover-shadow {
            transition: all 0.3s ease;
        }

        .hover-shadow:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
            transform: translateY(-2px);
        }

        /* ====== OFERTAS – Marquee “tela a tela” (SEM sobrepor) ======
               Usa: .ofertas-marquee > .ofertas-track > .oferta-slide
               (Set duplicado no HTML para loop perfeito)
            */
        .ofertas-marquee {
            overflow: hidden;
            -webkit-mask-image: linear-gradient(to right, transparent, #000 3% 97%, transparent);
            mask-image: linear-gradient(to right, transparent, #000 3% 97%, transparent);
        }


        .ofertas-track {
            --start: 60px;
            display: flex;
            flex-wrap: nowrap;
            align-items: stretch;
            gap: 1rem;
            width: max-content;
            will-change: transform;
            animation: ofertasMarquee var(--duration, 30s) linear infinite;
        }

        /* Pausa no hover (UX) */
        .ofertas-marquee:hover .ofertas-track {
            animation-play-state: paused;
        }

        /* Cada card ocupa um “slot” fixo */
        .oferta-slide {
            flex: 0 0 auto;
            width: 280px;
            /* ajuste aqui se quiser mais compacto */
        }

        /* Loop perfeito: anda metade do track (porque duplicamos Set1 + Set2) */
        @keyframes ofertasMarquee {
            from {
                transform: translateX(calc(-1 * var(--start)));
            }

            to {
                transform: translateX(calc(-50% - var(--start)));
            }

        }

        /* Card mais “vitrine” */
        .oferta-card {
            border-radius: 14px;
            overflow: hidden;
            transition: transform .18s ease, box-shadow .18s ease;
        }

        .oferta-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 28px rgba(0, 0, 0, .12) !important;
        }

        /* Topo com imagem */
        .oferta-img {
            height: 210px;
            background: linear-gradient(to bottom, #f8f9fa 0%, #ffffff 100%);
        }

        /* Título (2 linhas) */
        .oferta-title {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            min-height: 40px;
        }

        /* Favoritar */
        .btn-favoritar {
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }

        .btn-favoritar:hover {
            transform: scale(1.08);
        }

        .btn-favoritar.favoritado i {
            color: #dc3545 !important;
        }

        .btn-favoritar:not(.favoritado) i {
            color: #6c757d;
        }

        .btn-favoritar.favoritado i::before {
            content: "\f588";
        }

        /* Responsivo: cards menores no mobile */
        @media (max-width: 575.98px) {
            .oferta-slide {
                width: 240px;
            }

            .oferta-img {
                height: 200px;
            }
        }

        /* Acessibilidade: reduz movimento */
        @media (prefers-reduced-motion: reduce) {
            .ofertas-track {
                animation: none;
            }

            .ofertas-marquee {
                -webkit-mask-image: none;
                mask-image: none;
                overflow-x: auto;
            }
        }
    </style>
@endpush


@push('scripts')
    <!-- AOS Library -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Funcionalidade do botão favoritar
            const botoesfavoritar = document.querySelectorAll('.btn-favoritar');

            botoesfavoritar.forEach(botao => {
                botao.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const produtoId = this.getAttribute('data-produto-id');
                    this.classList.toggle('favoritado');

                    // Aqui você pode adicionar a lógica para salvar no backend
                    console.log('Produto ' + produtoId + ' favoritado: ' + this.classList.contains(
                        'favoritado'));
                });
            });
        });
    </script>
@endpush
