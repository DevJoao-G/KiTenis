@extends('layouts.app')

@section('title', 'Recuperar Senha')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">

                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">

                        <h1 class="h4 fw-bold mb-2">Recuperar senha</h1>
                        <p class="text-muted mb-4">
                            Digite seu e-mail e enviaremos um link para redefinir sua senha.
                        </p>

                    {{-- Alert de Sucesso (agora Toast) --}}
                    @if (session('status'))
                        <x-alert type="success" :message="session('status')" />
                    @endif

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">E-mail</label>
                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    class="form-control form-control-lg @error('email') is-invalid @enderror"
                                    placeholder="seuemail@exemplo.com"
                                    required
                                    autofocus
                                >

                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-success btn-lg w-100">
                                Enviar link de redefinição
                            </button>
                        </form>

                        <div class="text-center mt-4">
                            <a href="{{ route('login') }}" class="text-decoration-none text-success fw-semibold">
                                Voltar para o login
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
