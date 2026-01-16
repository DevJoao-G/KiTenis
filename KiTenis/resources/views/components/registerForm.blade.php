<form id="registerForm" novalidate>

    {{-- Nome --}}
    <div class="mb-3">
        <label class="form-label">Nome</label>

        <div class="position-relative">
            <i class="bi bi-person position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>

            <input
                type="text"
                name="name"
                class="form-control ps-5"
                placeholder="Seu nome completo"
                required
            >

            <div class="invalid-feedback"></div>
        </div>
    </div>

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

    {{-- Confirmar senha --}}
    <div class="mb-3">
        <label class="form-label">Confirmar senha</label>

        <div class="position-relative">
            <i class="bi bi-lock position-absolute top-50 start-0 translate-middle-y ms-3 text-muted fs-5"></i>

            <input
                type="password"
                name="password_confirmation"
                class="form-control ps-5"
                placeholder="Confirme sua senha"
                required
            >

            <div class="invalid-feedback"></div>
        </div>
    </div>

    {{-- Botão --}}
    <button
        type="submit"
        id="registerBtn"
        class="btn btn-dark w-100 d-flex align-items-center justify-content-center gap-2"
    >
        <span class="btn-text">Criar Conta</span>
        <span class="spinner-border spinner-border-sm d-none"></span>
    </button>

</form>
