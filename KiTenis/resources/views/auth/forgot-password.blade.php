@extends('layouts.app')

@section('title', 'Recuperar Senha')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            
            {{-- Card Principal --}}
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 p-md-5">
                    
                    {{-- Ícone e Título --}}
                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <i class="bi bi-key fs-1 text-success"></i>
                        </div>
                        <h3 class="fw-bold mb-2">Esqueceu sua senha?</h3>
                        <p class="text-muted small mb-0">
                            Sem problemas! Informe seu e-mail e enviaremos um link para redefinir sua senha.
                        </p>
                    </div>

                    {{-- Alert de Sucesso --}}
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- Formulário --}}
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        {{-- Email --}}
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">
                                E-mail
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input 
                                    type="email" 
                                    class="form-control border-start-0 @error('email') is-invalid @enderror" 
                                    id="email" 
                                    name="email" 
                                    value="{{ old('email') }}" 
                                    placeholder="seu@email.com"
                                    required 
                                    autofocus
                                >
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        {{-- Botão Enviar --}}
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-send me-2"></i>
                                Enviar Link de Recuperação
                            </button>
                        </div>

                        {{-- Link Voltar --}}
                        <div class="text-center">
                            <a href="{{ route('access') }}" class="text-decoration-none text-muted small">
                                <i class="bi bi-arrow-left me-1"></i>
                                Voltar para login
                            </a>
                        </div>
                    </form>

                </div>
            </div>

            {{-- Informações Adicionais --}}
            <div class="text-center mt-4">
                <p class="text-muted small mb-2">
                    <i class="bi bi-shield-check me-1"></i>
                    Suas informações estão seguras conosco
                </p>
                <p class="text-muted small">
                    Precisa de ajuda? 
                    <a href="#" class="text-success text-decoration-none">
                        Entre em contato
                    </a>
                </p>
            </div>

        </div>
    </div>
</div>
@endsection