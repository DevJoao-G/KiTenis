@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="col-12 col-sm-8 col-md-6 col-lg-4">

            <div class="mb-5 d-flex flex-column">
                <h1 class="text-center mb-2 fw-bold fs-1">Minha Conta</h1>
                <p class="text-center text-secondary fst-italic">Faça login ou crie uma conta para continuar</p>
                
                <div class="btn-group " role="group" aria-label="Escolha entre Login e Cadastro">
                    <button type="button" class="btn btn-secondary active" id="btn-login">
                        Entrar
                    </button>
                    <button type="button" class="btn btn-light" id="btn-cadastrar">
                        Criar Conta
                    </button>
                </div>
            </div>
            
            <!-- Container dos formulários -->
            <div class="forms-container">
                <div class="form-wrapper active" id="form-login">
                    <x-loginForm />
                </div>
                
                <div class="form-wrapper" id="form-cadastrar">
                    <x-registerForm />
                </div>
            </div>
            
        </div>
    </div>
</div>

@endsection