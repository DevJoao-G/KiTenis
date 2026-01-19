<footer class="bg-dark text-white mt-5 py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h5 class="fw-bold">🎾 KiTenis</h5>
                <p class="small">Os melhores tênis para você!</p>
            </div>
            
            <div class="col-md-4">
                <h6>Links Úteis</h6>
                <ul class="list-unstyled small">
                    <li><a href="{{ route('home') }}" class="text-white-50 text-decoration-none">Início</a></li>
                    <li><a href="{{ route('products.index') }}" class="text-white-50 text-decoration-none">Produtos</a></li>
                    <li><a href="#" class="text-white-50 text-decoration-none">Sobre</a></li>
                </ul>
            </div>
            
            <div class="col-md-4">
                <h6>Contato</h6>
                <p class="small text-white-50">
                    📧 contato@kitenis.com<br>
                    📱 (11) 9999-9999
                </p>
            </div>
        </div>
        
        <hr class="text-white-50">
        
        <div class="text-center small text-white-50">
            &copy; {{ date('Y') }} KiTenis - Todos os direitos reservados
        </div>
    </div>
</footer>