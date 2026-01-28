import { Toast } from 'bootstrap';

document.addEventListener('DOMContentLoaded', function () {
    const botoesfavoritar = document.querySelectorAll('.btn-favoritar');

    function showToast(message, variant = 'warning') {
        let container = document.querySelector('.toast-container');

        if (!container) {
            container = document.createElement('div');
            container.className = 'toast-container position-fixed top-0 end-0 p-3';
            container.style.zIndex = '2000';
            document.body.appendChild(container);
        }

        const toastEl = document.createElement('div');
        toastEl.className = `toast align-items-center text-bg-${variant} border-0`;
        toastEl.setAttribute('role', 'alert');
        toastEl.setAttribute('aria-live', 'assertive');
        toastEl.setAttribute('aria-atomic', 'true');

        toastEl.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Fechar"></button>
            </div>
        `;

        container.appendChild(toastEl);

        const toast = new Toast(toastEl, { delay: 3000, autohide: true });
        toast.show();

        toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
    }

    function setButtonState(button, favorited) {
        button.classList.toggle('favoritado', !!favorited);
        const icon = button.querySelector('i');

        if (icon) {
            icon.classList.toggle('bi-heart', !favorited);
            icon.classList.toggle('bi-heart-fill', favorited);
        }
    }

    botoesfavoritar.forEach(botao => {
        botao.addEventListener('click', async function (e) {
            e.preventDefault();
            e.stopPropagation();

            const isAuth = !!(window.KITENIS && window.KITENIS.auth);

            if (!isAuth) {
                showToast('Você precisa estar logado para favoritar produtos.', 'warning');
                return;
            }

            const url = this.getAttribute('data-url');
            if (!url) {
                showToast('Não foi possível favoritar agora. (URL ausente)', 'danger');
                return;
            }

            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            // UI otimista
            const wasFavorited = this.classList.contains('favoritado');
            setButtonState(this, !wasFavorited);

            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf ?? '',
                    },
                });

                if (res.status === 401) {
                    setButtonState(this, wasFavorited);
                    showToast('Faça login para favoritar produtos.', 'warning');
                    return;
                }

                if (!res.ok) {
                    setButtonState(this, wasFavorited);
                    showToast('Erro ao salvar favorito. Tente novamente.', 'danger');
                    return;
                }

                const data = await res.json();
                setButtonState(this, !!data.favorited);

                showToast(data.favorited ? 'Adicionado aos favoritos!' : 'Removido dos favoritos!', 'success');
            } catch (err) {
                setButtonState(this, wasFavorited);
                showToast('Erro de conexão. Tente novamente.', 'danger');
            }
        });
    });
});
