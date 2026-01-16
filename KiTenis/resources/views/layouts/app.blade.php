<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'KiTenis')</title>

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/header.js'])
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ route('Home') }}">KiTenis</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                    aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="mainNavbar">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Feminino</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Masculino</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Categorias
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="#">Esporte</a></li>
                                <li><a class="dropdown-item" href="#">Casual</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Infantil</a></li>
                            </ul>
                        </li>
                    </ul>

                    <form class="d-flex">
                        <!-- Ícone do usuário -->
                        <a href="#" class="text-white fs-4 me-3" id="userIcon" title="Usuário" data-bs-toggle="modal" data-bs-target="#userModal">
                            <i class="bi bi-person-circle"></i>
                        </a>
                        
                        <input class="form-control me-2" type="search" placeholder="Procure aqui..." aria-label="Search">
                        <button class="btn btn-outline-success" type="submit">Buscar</button>
                    </form>
                </div>
            </div>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <!-- Modal de Usuário -->
    <div class="modal fade" id="userModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">
                        <i class="bi bi-person-circle me-2"></i>Minha Conta
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Informações do Usuário -->
                    <div id="userInfo" style="display: none;">
                        <div class="text-center mb-3">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary text-white" 
                                 style="width: 80px; height: 80px; font-size: 2rem; font-weight: bold;" 
                                 id="userAvatar">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nome</label>
                            <p class="form-control-plaintext" id="userName"></p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <p class="form-control-plaintext" id="userEmail"></p>
                        </div>

                        <div class="d-grid gap-2">
                            <a href="/profile" class="btn btn-outline-primary">
                                <i class="bi bi-person me-2"></i>Meu Perfil
                            </a>
                            <a href="/orders" class="btn btn-outline-primary">
                                <i class="bi bi-bag me-2"></i>Meus Pedidos
                            </a>
                            <a href="/settings" class="btn btn-outline-primary">
                                <i class="bi bi-gear me-2"></i>Configurações
                            </a>
                        </div>
                    </div>

                    <!-- Mensagem para usuário não autenticado -->
                    <div id="notAuthenticatedInfo" style="display: none;">
                        <div class="text-center py-4">
                            <i class="bi bi-person-x fs-1 text-muted"></i>
                            <p class="mt-3 text-muted">Você não está conectado</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <!-- Botão de Logout (só aparece se autenticado) -->
                    <button type="button" id="logoutBtn" class="btn btn-danger" style="display: none;">
                        <i class="bi bi-box-arrow-right me-2"></i>
                        <span id="logoutText">Sair</span>
                    </button>
                    
                    <!-- Botão de Login (só aparece se não autenticado) -->
                    <a href="{{ route('Account') }}" id="loginBtn" class="btn btn-dark" style="display: none;">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Entrar
                    </a>
                    
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>