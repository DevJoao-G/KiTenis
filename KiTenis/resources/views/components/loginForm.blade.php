<form>
  <div class="mb-3">
    <label for="loginEmail" class="form-label">Email</label>

    <div class="position-relative mb-3">
      <i class="bi bi-envelope position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
      <input type="email" class="form-control ps-5" id="loginEmail" placeholder="Seu@email.com">
    </div>
  </div>

  <div class="mb-3">
    <label for="loginPassword" class="form-label">Senha</label>
    <div class="position-relative mb-3">
      <i class="bi bi-lock position-absolute top-50 start-0 translate-middle-y ms-3 text-muted fs-5"></i>
      <input type="password" class="form-control ps-5" id="loginPassword" placeholder="Sua senha">
    </div>
  </div>

  <div class="mb-3 d-flex justify-content-between align-items-center">
    <div class="form-check">
      <input type="checkbox" class="form-check-input" id="rememberMe">
      <label class="form-check-label" for="rememberMe">Lembrar-me</label>
    </div>
    <a href="#" class="text-decoration-none text-muted small">Esqueceu a senha?</a>
  </div>

  <button type="submit" class="btn btn-dark w-100">Entrar</button>
</form>