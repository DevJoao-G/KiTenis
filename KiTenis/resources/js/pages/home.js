import { Toast } from 'bootstrap';

document.addEventListener('DOMContentLoaded', function () {
    function ensureToastContainer() {
        let container = document.querySelector('.toast-container');
        if (!container) {
            container = document.createElement('div');
            container.className = 'toast-container position-fixed top-0 end-0 p-3';
            container.style.zIndex = '2000';
            document.body.appendChild(container);
        }
        return container;
    }

    function showToast(message, variant = 'warning') {
        const container = ensureToastContainer();

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
            icon.classList.toggle('text-danger', favorited);
        }
    }

    function getProductIdFromButton(button) {
        const fromData = button.getAttribute('data-produto-id') || button.getAttribute('data-product-id');
        if (fromData) return String(fromData);

        const url = button.getAttribute('data-url') || '';
        const m = url.match(/\/favoritos\/(\d+)\/toggle/);
        return m ? m[1] : null;
    }

    function syncAllButtons(productId, favorited) {
        if (!productId) return;

        // Home/ofertas costuma ter data-produto-id; show pode não ter.
        const byData = document.querySelectorAll(
            `.btn-favoritar[data-produto-id="${productId}"], .btn-favoritar[data-product-id="${productId}"]`
        );
        byData.forEach(btn => setButtonState(btn, favorited));

        // fallback por URL (cobre show)
        const byUrl = document.querySelectorAll(`.btn-favoritar[data-url*="/favoritos/${productId}/toggle"]`);
        byUrl.forEach(btn => setButtonState(btn, favorited));
    }

    async function toggleFavorite(button) {
        const url = button.getAttribute('data-url');
        if (!url) {
            showToast('Não foi possível favoritar agora. (URL ausente)', 'danger');
            return;
        }

        // ✅ trava para evitar clique duplo enquanto a request está em andamento
        if (button.dataset.favBusy === '1') return;
        button.dataset.favBusy = '1';

        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        // UI otimista (aplica em todos os botões do mesmo produto)
        const productId = getProductIdFromButton(button);
        const wasFavorited = button.classList.contains('favoritado');
        syncAllButtons(productId, !wasFavorited);

        try {
            const res = await fetch(url, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                },
            });

            // 401 = não autenticado
            if (res.status === 401) {
                syncAllButtons(productId, wasFavorited);
                showToast('Faça login para favoritar produtos.', 'warning');
                return;
            }

            // 419 = CSRF/sessão expirada (não trate como "não logado")
            if (res.status === 419) {
                syncAllButtons(productId, wasFavorited);
                showToast('Sessão expirada. Recarregando...', 'warning');
                setTimeout(() => window.location.reload(), 900);
                return;
            }

            if (!res.ok) {
                syncAllButtons(productId, wasFavorited);
                showToast('Erro ao salvar favorito. Tente novamente.', 'danger');
                return;
            }

            const data = await res.json().catch(() => null);

            if (!data?.ok) {
                syncAllButtons(productId, wasFavorited);
                showToast('Erro ao salvar favorito. Tente novamente.', 'danger');
                return;
            }

            const finalProductId = String(data.product_id ?? productId ?? '');
            const isOn = !!data.favorited;

            syncAllButtons(finalProductId, isOn);
            showToast(isOn ? 'Adicionado aos favoritos!' : 'Removido dos favoritos!', 'success');
        } catch (err) {
            syncAllButtons(productId, wasFavorited);
            showToast('Erro de conexão. Tente novamente.', 'danger');
        } finally {
            button.dataset.favBusy = '0';
        }
    }

    // ✅ Delegação: evita bind duplicado (principal causa do "desmarca e volta")
    document.addEventListener('click', function (e) {
        const button = e.target.closest('.btn-favoritar[data-url]');
        if (!button) return;

        e.preventDefault();
        e.stopPropagation();

        toggleFavorite(button);
    }, { passive: false });
});
