<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bungee+Shade&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/kitenis-icon.png') }}" type="image/png">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'KiTenis') }} - @yield('title', 'Loja de Tênis')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <!-- Navbar -->
    <x-navbar />

    <!-- Alerts -->
    @if (session('success'))
        <x-alert type="success" :message="session('success')" />
    @endif

    @if (session('error'))
        <x-alert type="danger" :message="session('error')" />
    @endif

    <!-- Main Content -->
    <main class="py-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <x-footer />
</body>

</html>
