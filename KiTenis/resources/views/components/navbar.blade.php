{{-- resources/views/components/navbar.blade.php --}}
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow">
    <div class="container-fluid">
        <!-- Logo -->
        <a class="navbar-brand font-bungee" href="{{ route('home') }}">
            KiTenis
        </a>

        <!-- Toggle mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Menu principal -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        Início
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('products.index') ? 'active' : '' }}"
                        href="{{ route('products.index') }}">
                        Produtos
                    </a>
                </li>

                <!-- Dropdown categorias -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        Categorias
                    </a>

                    <ul class="dropdown-menu dropdown-dark">
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('products.masculino') ? 'bg-success text-white' : '' }}"
                                href="{{ route('products.masculino') }}">
                                Masculino
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item {{ request()->routeIs('products.feminino') ? 'bg-success text-white' : '' }}"
                                href="{{ route('products.feminino') }}">
                                Feminino
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item {{ request()->routeIs('products.infantil') ? 'bg-success text-white' : '' }}"
                                href="{{ route('products.infantil') }}">
                                Infantil
                            </a>
                        </li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a class="dropdown-item fw-bold dropdown-ofertas {{ request()->routeIs('products.ofertas') ? 'bg-success text-white' : '' }}"
                                href="{{ route('products.ofertas') }}">
                                Ofertas
                            </a>
                        </li>
                    </ul>

                </li>
            </ul>

            <!-- Busca -->
            <form class="d-flex me-3" method="GET" action="{{ route('products.index') }}">
                <input class="form-control form-control-sm me-2" type="search" name="search"
                    placeholder="Buscar produto ou marca" value="{{ request('search') }}" style="min-width: 190px;">
                <button class="btn btn-success btn-sm" type="submit">
                    <i class="bi bi-search"></i>
                    <span class="d-none d-md-inline ms-1">Buscar</span>
                </button>
            </form>

            <!-- Auth -->
            <ul class="navbar-nav">
                @auth
                    <!-- Carrinho -->
                    @php
                        $cartCount = collect(session('cart', []))->sum('quantity');
                    @endphp

                    <li class="nav-item me-2">
                        <a href="{{ route('cart.index') }}" class="nav-link position-relative d-flex align-items-center">
                            <i class="bi bi-cart3 fs-5"></i>

                            <span class="badge bg-success position-absolute top-0 start-100 translate-middle"
                                id="cartCountBadge" style="font-size: .7rem;">
                                {{ $cartCount ?? 0 }}
                            </span>
                        </a>
                    </li>

                    <!-- Usuário -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle fs-5"></i>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end dropdown-dark">
                            <li class="px-3 py-2 small dropdown-user-name">
                                {{ Auth::user()->name }}
                            </li>

                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>
                                <a class="dropdown-item" href="{{ route('account') }}">
                                    <i class="bi bi-person me-2"></i>Minha Conta
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="{{ route('orders.index') }}">
                                    <i class="bi bi-bag-check me-2"></i>Meus Pedidos
                                </a>
                            </li>

                            @if(Auth::user()->is_admin)
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item fw-bold dropdown-admin dropdown-inverse"
                                        href="{{ route('filament.admin.pages.dashboard') }}">
                                        <i class="bi bi-shield-lock me-2"></i>Painel Admin
                                    </a>
                                </li>
                            @endif

                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right me-2"></i>Sair
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    @guest
                        <li class="nav-item">
                            <a href="{{ route('access') }}" class="nav-link d-flex align-items-center">
                                <i class="bi bi-person-circle fs-4"></i>
                                <span class="small ms-2">Entrar</span>
                            </a>
                        </li>
                    @endguest
                @endauth
            </ul>
        </div>
    </div>
</nav>
