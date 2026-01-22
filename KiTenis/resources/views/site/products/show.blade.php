@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="row g-4">

        {{-- Imagem --}}
        <div class="col-md-6">
            <div class="border rounded p-3 text-center bg-light">
                <img 
                    src="{{ $product->image ?? 'https://via.placeholder.com/500x500?text=KiTenis' }}" 
                    class="img-fluid"
                    alt="{{ $product->name }}"
                >
            </div>
        </div>

        {{-- Informações --}}
        <div class="col-md-6">

            {{-- Categoria --}}
            <span class="badge bg-secondary mb-2 text-capitalize">
                {{ $product->category }}
            </span>

            {{-- Nome --}}
            <h2 class="fw-bold">
                {{ $product->name }}
            </h2>

            {{-- Preço --}}
            <p class="fs-3 fw-bold text-success mt-3">
                R$ {{ number_format($product->price, 2, ',', '.') }}
            </p>

            {{-- Estoque --}}
            @if($product->stock > 0)
                <p class="text-success fw-semibold">
                    Em estoque ({{ $product->stock }} unidades)
                </p>
            @else
                <p class="text-danger fw-semibold">
                    Produto indisponível
                </p>
            @endif

            {{-- Descrição --}}
            <p class="mt-4 text-muted">
                {{ $product->description }}
            </p>

            {{-- Ações --}}
            <div class="d-flex gap-2 mt-4">
                <button class="btn btn-success btn-lg" {{ $product->stock == 0 ? 'disabled' : '' }}>
                    <i class="bi bi-cart-plus">
