const HOME_ROUTE = '/';

/**
 * Retorna o token salvo
 */
function getToken() {
    return localStorage.getItem('token');
}

/**
 * Verifica se o usuário está autenticado (API token)
 */
async function checkAuth() {
    const token = getToken();

    if (!token) {
        updateUserInterface(null, false);
        return;
    }

    try {
        const response = await fetch('/api/me', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`,
            },
        });

        if (!response.ok) {
            throw new Error('Não autenticado');
        }

        const user = await response.json();

        if (!user || !user.name) {
            throw new Error('Usuário inválido');
        }

        updateUserInterface(user, true);

    } catch (error) {
        console.error('Erro ao verificar autenticação:', error);
        localStorage.removeItem('token');
        updateUserInterface(null, false);
    }
}

/**
 * Atualiza a interface
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
        document.getElementById('userName').textContent = user.name;
        document.getElementById('userEmail').textContent = user.email;
        document.getElementById('userAvatar').textContent =
            user.name.charAt(0).toUpperCase();

        userInfoEl.style.display = 'block';
        notAuthenticatedEl.style.display = 'none';
        logoutBtnEl.style.display = 'inline-block';
        loginBtnEl.style.display = 'none';
    } else {
        userInfoEl.style.display = 'none';
        notAuthenticatedEl.style.display = 'block';
        logoutBtnEl.style.display = 'none';
        loginBtnEl.style.display = 'inline-block';
    }
}

/**
 * Logout (API token)
 */
async function handleLogout() {
    const token = getToken();
    if (!token) return;

    try {
        await fetch('/api/logout', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`,
            },
        });
    } catch (error) {
        console.error('Erro no logout:', error);
    } finally {
        localStorage.removeItem('token');
        window.location.href = HOME_ROUTE;
    }
}

/**
 * Inicialização
 */
function initHeader() {
    checkAuth();

    const logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', handleLogout);
    }

    const modalElement = document.getElementById('userModal');
    if (modalElement) {
        modalElement.addEventListener('show.bs.modal', checkAuth);
    }
}

document.readyState === 'loading'
    ? document.addEventListener('DOMContentLoaded', initHeader)
    : initHeader();
