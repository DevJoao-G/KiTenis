@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
       <div class="text-center mb-4">
    <h1 class="fw-bold fs-1">Minha Conta</h1>
    <p class="text-secondary fst-italic">
        Faça login ou crie uma conta para continuar
    </p>

    <div class="btn-group mt-3" role="group">
        <button type="button" class="btn btn-success active" id="btn-login">
            Entrar
        </button>
        <button type="button" class="btn btn-outline-success" id="btn-cadastrar">
            Criar Conta
        </button>
    </div>
</div>

<div class="forms-container mt-4">
    <div class="form-wrapper active" id="form-login">
        @include('auth.login')
    </div>

    <div class="form-wrapper d-none" id="form-cadastrar">
        @include('auth.register')
    </div>
</div>

</div>
@endsection

@push('scripts')
<script>
    const btnLogin = document.getElementById('btn-login');
    const btnRegister = document.getElementById('btn-register');
    const formLogin = document.getElementById('form-login');
    const formRegister = document.getElementById('form-register');

    btnLogin.addEventListener('click', () => {
        btnLogin.classList.add('btn-success', 'active');
        btnLogin.classList.remove('btn-outline-success');

        btnRegister.classList.remove('btn-success', 'active');
        btnRegister.classList.add('btn-outline-success');

        formLogin.classList.remove('d-none');
        formRegister.classList.add('d-none');
    });

    btnRegister.addEventListener('click', () => {
        btnRegister.classList.add('btn-success', 'active');
        btnRegister.classList.remove('btn-outline-success');

        btnLogin.classList.remove('btn-success', 'active');
        btnLogin.classList.add('btn-outline-success');

        formRegister.classList.remove('d-none');
        formLogin.classList.add('d-none');
    });
</script>
@endpush
