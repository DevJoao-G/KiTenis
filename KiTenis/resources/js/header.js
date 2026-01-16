// resources/js/header.js

// Pega o CSRF token
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

// URL de redirecionamento
const HOME_ROUTE = '/';

/**
 * Verifica se o usuário está autenticado
 */
async function checkAuth() {
    try {
        const response = await fetch('/api/me', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            credentials: 'include'
        });

        if (response.ok) {
            const user = await response.json();
            updateUserInterface(user, true);
        } else {
            console.log('Usuário não autenticado');
            updateUserInterface(null, false);
        }
    } catch (error) {
        console.error('Erro ao verificar autenticação:', error);
        updateUserInterface(null, false);
    }
}

/**
 * Atualiza a interface com os dados do usuário
 */
function updateUserInterface(user, isAuthenticated) {
    const userInfoEl = document.getElementById('userInfo');
    const notAuthenticatedEl = document.getElementById('notAuthenticatedInfo');
    const logoutBtnEl = document.getElementById('logoutBtn');
    const loginBtnEl = document.getElementById('loginBtn');

    if (!userInfoEl || !notAuthenticatedEl || !logoutBtnEl || !loginBtnEl) {
        return;
    }

    if (isAuthenticated && user) {
        // Mostra informações do usuário
        document.getElementById('userName').textContent = user.name;
        document.getElementById('userEmail').textContent = user.email;
        document.getElementById('userAvatar').textContent = user.name.charAt(0).toUpperCase();
        
        userInfoEl.style.display = 'block';
        notAuthenticatedEl.style.display = 'none';
        logoutBtnEl.style.display = 'inline-block';
        loginBtnEl.style.display = 'none';
    } else {
        // Mostra mensagem de não autenticado
        userInfoEl.style.display = 'none';
        notAuthenticatedEl.style.display = 'block';
        logoutBtnEl.style.display = 'none';
        loginBtnEl.style.display = 'inline-block';
    }
}

/**
 * Realiza o logout do usuário
 */
async function handleLogout() {
    const logoutBtn = document.getElementById('logoutBtn');
    const logoutText = document.getElementById('logoutText');

    if (!logoutBtn || !logoutText) {
        return;
    }

    // Desabilita o botão e mostra loading
    logoutBtn.disabled = true;
    logoutText.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saindo...';

    try {
        const response = await fetch('/api/logout', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            credentials: 'include'
        });

        if (response.ok) {
            // Fecha o modal
            const modalElement = document.getElementById('userModal');
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) {
                modal.hide();
            }

            // Redireciona para a home usando window.location
            window.location.href = HOME_ROUTE;
        } else {
            throw new Error('Erro ao fazer logout');
        }
    } catch (error) {
        console.error('Erro no logout:', error);
        alert('Erro ao fazer logout. Tente novamente.');

        // Reabilita o botão
        logoutBtn.disabled = false;
        logoutText.innerHTML = '<i class="bi bi-box-arrow-right me-2"></i>Sair';
    }
}

/**
 * Atualiza os dados do usuário quando o modal é aberto
 */
function handleModalOpen() {
    checkAuth();
}

/**
 * Inicializa os event listeners do header
 */
function initHeader() {
    // Verifica autenticação ao carregar
    checkAuth();

    // Adiciona event listener ao botão de logout
    const logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', handleLogout);
    }

    // Adiciona event listener ao modal
    const modalElement = document.getElementById('userModal');
    if (modalElement) {
        modalElement.addEventListener('show.bs.modal', handleModalOpen);
    }
}

// Inicializa quando o DOM estiver pronto
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initHeader);
} else {
    initHeader();
}