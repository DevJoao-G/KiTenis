@extends('layouts.app')

@section('title', 'Início')

@section('content')
<div class="container vh-100 d-flex flex-column justify-content-center align-items-center text-center">
    <div>
        <h1 class="display-4 fw-bold mb-3">Bem-vindo ao KiTenis!</h1>
        <p class="lead mb-4">
            Descubra os melhores tênis para todas as ocasiões. Qualidade, conforto e estilo em um só lugar.
        </p>
        <a href="{{ route('products.index') }}" class="btn btn-success btn-lg px-4">
            Ver Produtos
        </a>
    </div>
    

</div>
@endsection