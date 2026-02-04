@extends('layouts.app')

@section('title', 'Minha Conta')

@section('content')
<div class="container py-5">

    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
        <div>
            <h1 class="fw-bold mb-1">Minha Conta</h1>
            <div class="text-muted">Gerencie seus dados e acompanhe seus pedidos.</div>
        </div>

        <div class="d-grid gap-2 d-sm-flex">
            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-bag-check me-1"></i> Meus Pedidos
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-success" type="submit">
                    <i class="bi bi-box-arrow-right me-1"></i> Sair
                </button>
            </form>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-dark text-white d-flex align-items-center justify-content-center"
                             style="width: 54px; height: 54px;">
                            <i class="bi bi-person fs-3"></i>
                        </div>

                        <div>
                            <div class="fw-semibold">{{ $user->name }}</div>
                            <div class="text-muted small">{{ $user->email }}</div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="small text-muted mb-2">Ações rápidas</div>

                    <div class="d-grid gap-2">
                        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-bag-check me-2"></i> Ver meus pedidos
                        </a>

                        <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-handbag me-2"></i> Ir para o carrinho
                        </a>

                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-shop me-2"></i> Continuar comprando
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Dados do usuário</h5>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Nome</label>
                            <div class="fw-semibold">{{ $user->name }}</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted small">E-mail</label>
                            <div class="fw-semibold">{{ $user->email }}</div>
                        </div>

                        <div class="col-12">
                            <div class="alert alert-warning mb-0">
                                <i class="bi bi-info-circle me-1"></i>
                                Edição de perfil ainda não foi implementada. Quando você criar a página de perfil,
                                eu conecto aqui no botão “Editar dados”.
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-end">
                        <button class="btn btn-success" type="button" disabled>
                            <i class="bi bi-pencil-square me-2"></i> Editar dados (em breve)
                        </button>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
@endsection
