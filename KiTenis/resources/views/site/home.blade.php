@extends('layouts.app')

@section('title', 'Início')

@section('content')
<div class="container">
    
    <!-- Hero Section -->
    <div class="row align-items-center mb-5 py-5 bg-light rounded">
        <div class="col-md-6">
            <h1 class="display-4 fw-bold">Bem-vindo à KiTenis! 🎾</h1>
            <p class="lead">Os melhores tênis para você correr, jogar e viver com estilo.</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                Ver Produtos
            </a>
        </div>
        <div class="col-md-6 text-center">
            <div class="display-1">👟</div>
        </div>
    </div>
    
    <!-- Destaques -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold mb-4">Destaques</h2>
        </div>
        
        @for ($i = 1; $i <= 3; $i++)
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="display-3 mb-3">🏃‍♂️</div>
                    <h5 class="card-title">Tênis {{ $i }}</h5>
                    <p class="card-text text-muted">Descrição do produto...</p>
                    <p class="fw-bold text-primary fs-4">R$ 299,90</p>
                    <a href="#" class="btn btn-outline-primary">Ver Detalhes</a>
                </div>
            </div>
        </div>
        @endfor
    </div>
    
</div>
@endsection