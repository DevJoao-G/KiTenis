
        document.addEventListener('DOMContentLoaded', function() {
            // Funcionalidade do botão favoritar
            const botoesfavoritar = document.querySelectorAll('.btn-favoritar');

            botoesfavoritar.forEach(botao => {
                botao.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const produtoId = this.getAttribute('data-produto-id');
                    this.classList.toggle('favoritado');

                    // Aqui você pode adicionar a lógica para salvar no backend
                    console.log('Produto ' + produtoId + ' favoritado: ' + this.classList.contains(
                        'favoritado'));
                });
            });
        });
