<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand font-bungee" href="{{ route('home') }}">
            KiTenis
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Links Principais -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        Início
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                        Produtos
                    </a>
                </li>
            </ul>
            
            <!-- Auth Links -->
            <ul class="navbar-nav">
                @auth
                    <!-- Carrinho -->
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            🛒 Carrinho 
                            <span class="badge bg-primary rounded-pill">0</span>
                        </a>
                    </li>
                    
                    <!-- Dropdown do Usuário -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('account') }}">
                                    👤 Minha Conta
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('orders.index') }}">
                                    📦 Meus Pedidos
                                </a>
                            </li>
                            
                            @if(Auth::user()->is_admin)
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-primary fw-bold" href="{{ route('admin.dashboard') }}">
                                        ⚙️ Painel Admin
                                    </a>
                                </li>
                            @endif
                            
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        🚪 Sair
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link btn btn-success" href="{{ route('login') }}">Entrar</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary  ms-2" href="{{ route('register') }}">
                            Criar Conta
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>