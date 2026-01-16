<form id="loginForm" novalidate>

    {{-- Email --}}
    <div class="mb-3">
        <label class="form-label">Email</label>

        <div class="position-relative">
            <i class="bi bi-envelope position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>

            <input
                type="email"
                name="email"
                class="form-control ps-5"
                placeholder="seu@email.com"
                required
            >

            <div class="invalid-feedback"></div>
        </div>
    </div>

    {{-- Senha --}}
    <div class="mb-3">
        <label class="form-label">Senha</label>

        <div class="position-relative">
            <i class="bi bi-lock position-absolute top-50 start-0 translate-middle-y ms-3 text-muted fs-5"></i>

            <input
                type="password"
                name="password"
                class="form-control ps-5"
                placeholder="Sua senha"
                required
            >

            <div class="invalid-feedback"></div>
        </div>
    </div>

    {{-- Lembrar-me + Esqueceu senha --}}
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <div class="form-check">
            <input
                type="checkbox"
                name="remember"
                class="form-check-input"
                id="rememberMe"
            >
            <label class="form-check-label" for="rememberMe">
                Lembrar-me
            </label>
        </div>

        <a href="#" class="text-decoration-none text-muted small">
            Esqueceu a senha?
        </a>
    </div>

    {{-- Botão --}}
    <button
        type="submit"
        id="loginBtn"
        class="btn btn-dark w-100 d-flex align-items-center justify-content-center gap-2"
    >
        <span class="btn-text">Entrar</span>
        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
    </button>

</form>
