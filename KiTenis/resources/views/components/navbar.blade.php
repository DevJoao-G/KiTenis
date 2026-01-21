<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
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
                    <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}"
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
                            <a class="dropdown-item" href="#">
                                Masculino
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item" href="#">
                                Feminino
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item" href="#">
                                Infantil
                            </a>
                        </li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item fw-bold dropdown-offer" href="#">
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
                <button class="btn btn-success" type="submit">
                    Buscar
                </button>
            </form>

            <!-- Auth -->
            <ul class="navbar-nav">
                @auth
                    <!-- Carrinho -->
                    <li class="nav-item">
                        <a class="nav-link d-inline-flex align-items-center" href="#">
                            <i class="bi bi-handbag fs-5"></i>

                            <span class="badge rounded-pill bg-success ms-1 d-none d-lg-inline">
                                0
                            </span>
                        </a>
                    </li>

                    <!-- Usuário -->
                    <li class="nav-item dropdown ">
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
                                    Minha Conta
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="{{ route('orders.index') }}">
                                    Meus Pedidos
                                </a>
                            </li>

                            @if(Auth::user()->is_admin)
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item fw-bold dropdown-admin" href="{{ route('admin.dashboard') }}">
                                        Painel Admin
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
                                        Sair
                                    </button>
                                </form>
                            </li>
                        </ul>

                    </li>
                @else
                    @guest
                        <li class="nav-item ">
                            <a href="{{ route('access') }}" class="nav-link d-flex  align-items-center">
                                <i class="bi bi-person-circle fs-4 "></i>
                                <span class="small ms-2">Entrar</span>
                            </a>
                        </li>
                    @endguest
                @endauth
            </ul>
        </div>
    </div>
</nav>