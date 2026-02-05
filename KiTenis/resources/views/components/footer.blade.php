{{-- resources/views/components/footer.blade.php --}}
<footer class="footer bg-dark text-white py-4 mt-auto w-100">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-3 mb-md-0">
                <h5 class="fw-bold font-bungee">🎾 KiTenis</h5>
                <p class="small text-white-50">Os melhores tênis para você!</p>
                
                <!-- Redes Sociais -->
                <div class="d-flex gap-3 mt-3">
                    <a href="#" class="text-white-50" title="Facebook">
                        <i class="bi bi-facebook fs-5"></i>
                    </a>
                    <a href="#" class="text-white-50" title="Instagram">
                        <i class="bi bi-instagram fs-5"></i>
                    </a>
                    <a href="#" class="text-white-50" title="Twitter">
                        <i class="bi bi-twitter fs-5"></i>
                    </a>
                </div>
            </div>
            
            <div class="col-md-4 mb-3 mb-md-0">
                <h6 class="fw-bold">Links Úteis</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2">
                        <a href="{{ route('home') }}" class="text-white-50 text-decoration-none hover-success">
                            <i class="bi bi-chevron-right me-1"></i>Início
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('products.index') }}" class="text-white-50 text-decoration-none hover-success">
                            <i class="bi bi-chevron-right me-1"></i>Produtos
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-white-50 text-decoration-none hover-success">
                            <i class="bi bi-chevron-right me-1"></i>Sobre Nós
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-white-50 text-decoration-none hover-success">
                            <i class="bi bi-chevron-right me-1"></i>Política de Privacidade
                        </a>
                    </li>
                </ul>
            </div>
            
            <div class="col-md-4">
                <h6 class="fw-bold">Contato</h6>
                <ul class="list-unstyled small text-white-50">
                    <li class="mb-2">
                        <i class="bi bi-envelope me-2"></i>
                        <a href="mailto:contato@kitenis.com" class="text-white-50 text-decoration-none">
                            contato@kitenis.com
                        </a>
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-telephone me-2"></i>
                        <a href="tel:11999999999" class="text-white-50 text-decoration-none">
                            (11) 9999-9999
                        </a>
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-geo-alt me-2"></i>
                        Niterói, RJ - Brasil
                    </li>
                </ul>
                
                <!-- Formas de Pagamento -->
                <div class="mt-3">
                    <small class="text-white-50 d-block mb-2">Aceitamos:</small>
                    <div class="d-flex gap-2">
                        <i class="bi bi-credit-card fs-4 text-white-50"></i>
                        <i class="bi bi-paypal fs-4 text-white-50"></i>
                        <i class="bi bi-cash fs-4 text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <hr class="text-white-50 my-3">
        
        <div class="row">
            <div class="col-md-6 text-center text-md-start">
                <small class="text-white-50">
                    &copy; {{ date('Y') }} KiTenis. Todos os direitos reservados.
                </small>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <small class="text-white-50">
                    Desenvolvido por João Vitor Guidoti
                </small>
            </div>
        </div>
    </div>
</footer>