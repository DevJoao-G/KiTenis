document.addEventListener('DOMContentLoaded', function () {
    const botoesfavoritar = document.querySelectorAll('.btn-favoritar');

    botoesfavoritar.forEach(botao => {
        botao.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            const produtoId = this.getAttribute('data-produto-id');
            const favoritado = this.classList.toggle('favoritado');

            const icon = this.querySelector('i');
            if (icon) {
                icon.classList.toggle('bi-heart', !favoritado);
                icon.classList.toggle('bi-heart-fill', favoritado);
            }

            console.log('Produto ' + produtoId + ' favoritado: ' + favoritado);
        });
    });
});
