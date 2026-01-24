@extends('layouts.app')

@section('title', 'Início')

@section('content')

{{-- CAROUSEL DE OFERTAS --}}
<section class="bg-light py-5" data-aos="fade-up" data-aos-duration="1000">
    <div class="container">
        <div class="text-center mb-4">
            <h2 class="fw-bold fs-1">Ofertas da Semana</h2>
            <p class="text-secondary">Os melhores tênis com até 42% de desconto!</p>
        </div>

        <div id="carouselOfertas" class="carousel slide position-relative" data-bs-ride="carousel">
            
            {{-- Indicadores --}}
            <div class="carousel-indicators">
                @foreach($ofertas->chunk(3) as $index => $chunk)
                    <button 
                        type="button" 
                        data-bs-target="#carouselOfertas" 
                        data-bs-slide-to="{{ $index }}" 
                        class="{{ $index === 0 ? 'active' : '' }}"
                        aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                        aria-label="Slide {{ $index + 1 }}">
                    </button>
                @endforeach
            </div>

            {{-- Slides --}}
            <div class="carousel-inner">
                @foreach($ofertas->chunk(3) as $index => $chunk)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                        <div class="row g-4 px-5">
                            @foreach($chunk as $produto)
                                <div class="col-md-4">
                                    <div class="card h-100 shadow-sm border-0 position-relative" data-aos="zoom-in-up" data-aos-duration="800">
                                        
                                        {{-- Badge de Desconto --}}
                                        @php
                                            $desconto = round((1 - $produto->price / 600) * 100);
                                        @endphp
                                        <div class="position-absolute top-0 start-0 m-3" style="z-index: 10;">
                                            <span class="badge bg-success fs-6">
                                                -{{ $desconto }}% OFF
                                            </span>
                                        </div>

                                        {{-- Botão Favoritar --}}
                                        <button 
                                            class="btn-favoritar position-absolute top-0 end-0 m-3 bg-white rounded-circle border-0 shadow-sm"
                                            data-produto-id="{{ $produto->id }}"
                                            style="width: 40px; height: 40px; z-index: 10;"
                                        >
                                            <i class="bi bi-heart fs-5 text-danger"></i>
                                        </button>

                                        {{-- Imagem --}}
                                        <img 
                                            src="{{ $produto->image_url }}" 
                                            class="card-img-top p-4" 
                                            alt="{{ $produto->name }}"
                                            style="height: 250px; object-fit: contain;"
                                        >

                                        <div class="card-body d-flex flex-column">
                                            {{-- Avaliação --}}
                                            <div class="mb-2">
                                                <span class="text-warning">★★★★★</span>
                                            </div>

                                            {{-- Nome --}}
                                            <h6 class="card-title fw-semibold mb-2">
                                                {{ $produto->name }}
                                            </h6>

                                            {{-- Preços --}}
                                            <div class="mb-3">
                                                <small class="text-muted text-decoration-line-through">
                                                    R$ 599,90
                                                </small>
                                                <div class="fs-4 fw-bold text-success">
                                                    R$ {{ number_format($produto->price, 2, ',', '.') }}
                                                </div>
                                                <small class="text-muted">
                                                    ou 4x de R$ {{ number_format($produto->price / 4, 2, ',', '.') }}
                                                </small>
                                            </div>

                                            {{-- Botões --}}
                                            <div class="mt-auto">
                                                <a 
                                                    href="{{ route('products.show', $produto->id) }}" 
                                                    class="btn btn-outline-success w-100 mb-2"
                                                >
                                                    Ver detalhes
                                                </a>
                                                
                                                @if($produto->stock > 0)
                                                    <small class="text-success d-block text-center">
                                                        <i class="bi bi-check-circle"></i> 
                                                        Frete grátis em 3 dias úteis
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Controles --}}
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselOfertas" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselOfertas" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Próximo</span>
            </button>
        </div>

        {{-- Link Ver Todas --}}
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
            @foreach($marcas as $marca)
                <div class="col-6 col-md-2">
                    <a 
                        href="{{ route('products.index', ['search' => $marca['nome']]) }}" 
                        class="card h-100 text-decoration-none border hover-shadow"
                        data-aos="flip-up" data-aos-delay="100" data-aos-duration="1000"
                    >
                        <div class="card-body d-flex align-items-center justify-content-center p-4">
                            <img 
                                src="{{ asset('images/marcas/' . $marca['logo']) }}" 
                                alt="{{ $marca['nome'] }}"
                                class="img-fluid"
                                style="max-height: 60px; filter: grayscale(100%);"
                                onmouseover="this.style.filter='grayscale(0%)'"
                                onmouseout="this.style.filter='grayscale(100%)'"
                            >
                        </div>
                    </a>
                </div>
            @endforeach
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
                <form method="POST" action="{{ route('newsletter.subscribe') }}" class="row g-3 justify-content-center">
                    @csrf
                    <div class="col-md-4">
                        <input type="text" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror" placeholder="Seu nome" value="{{ old('name') }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <input type="email" name="email" class="form-control form-control-lg @error('email') is-invalid @enderror" placeholder="Seu e-mail" value="{{ old('email') }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
.hover-shadow {
    transition: all 0.3s ease;
}

.hover-shadow:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    transform: translateY(-2px);
}

/* Controles do carousel com fundo escuro e visíveis */
.carousel-control-prev-icon,
.carousel-control-next-icon {
    width: 3rem;
    height: 3rem;
    background-color: rgba(0, 0, 0, 0.7);
    border-radius: 50%;
    background-size: 50%;
}

.carousel-control-prev,
.carousel-control-next {
    width: 5%;
    opacity: 1;
}

.carousel-control-prev:hover .carousel-control-prev-icon,
.carousel-control-next:hover .carousel-control-next-icon {
    background-color: rgba(0, 0, 0, 0.9);
}

/* Botão favoritar */
.btn-favoritar {
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-favoritar:hover {
    transform: scale(1.1);
}

.btn-favoritar.favoritado i {
    color: #dc3545 !important;
}

.btn-favoritar:not(.favoritado) i {
    color: #6c757d;
}

.btn-favoritar.favoritado i::before {
    content: "\f588"; /* ícone de coração preenchido */
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
            // Por exemplo, fazer uma requisição AJAX
            console.log('Produto ' + produtoId + ' favoritado: ' + this.classList.contains('favoritado'));
        });
    });
});
</script>
@endpush