import { showToast, showMultipleToasts } from './toast';

/**
 * Lê cookie por nome
 */
function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return decodeURIComponent(parts.pop().split(';').shift());
}

/**
 * Garante CSRF + envia header corretamente
 */
async function apiRequest(url, data, btn) {
    const spinner = btn.querySelector('.spinner-border');
    const text = btn.querySelector('.btn-text');

    btn.disabled = true;
    spinner.classList.remove('d-none');
    text.classList.add('d-none');

    try {
        // 1️⃣ Cria cookies CSRF
        await fetch('/sanctum/csrf-cookie', {
            credentials: 'same-origin',
        });

        // 2️⃣ Lê token do cookie
        const csrfToken = getCookie('XSRF-TOKEN');

        // 3️⃣ Envia token no header
        const response = await fetch(url, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-XSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify(data),
        });

        const result = await response.json();

        if (!response.ok) {
            throw result;
        }

        return result;

    } finally {
        btn.disabled = false;
        spinner.classList.add('d-none');
        text.classList.remove('d-none');
    }
}

/* =====================
   HELPERS
===================== */

function clearErrors(form) {
    form.querySelectorAll('.is-invalid').forEach(el => {
        el.classList.remove('is-invalid');
    });
}

function showErrors(form, errors) {
    // Marca os campos como inválidos (borda vermelha)
    Object.keys(errors).forEach(field => {
        const input = form.querySelector(`[name="${field}"]`);
        if (input) {
            input.classList.add('is-invalid');
            
            // Se o erro é no campo password, marca também o password_confirmation
            if (field === 'password') {
                const confirmInput = form.querySelector(`[name="password_confirmation"]`);
                if (confirmInput) {
                    confirmInput.classList.add('is-invalid');
                }
            }
        }
    });

    // Converte os erros em array de mensagens
    const errorMessages = Object.values(errors).flat();
    
    // Exibe um toast para cada erro
    showMultipleToasts(errorMessages, 'error');
}

/* =====================
   REGISTER
===================== */

document.getElementById('registerForm')?.addEventListener('submit', async e => {
    e.preventDefault();

    const form = e.target;
    clearErrors(form);

    const data = Object.fromEntries(new FormData(form));
    const btn = document.getElementById('registerBtn');

    try {
        const res = await apiRequest('/api/register', data, btn);

        localStorage.setItem('token', res.token);
        showToast('Conta criada com sucesso!', 'success');
        
        // Aguarda 1.5 segundos antes de redirecionar para o usuário ver o toast
        setTimeout(() => {
            window.location.href = '/';
        }, 1500);

    } catch (err) {
        if (err.errors) showErrors(form, err.errors);
        else showToast(err.message || 'Erro ao cadastrar', 'error');
    }
});

/* =====================
   LOGIN
===================== */

document.getElementById('loginForm')?.addEventListener('submit', async e => {
    e.preventDefault();

    const form = e.target;
    clearErrors(form);

    const data = Object.fromEntries(new FormData(form));
    const btn = document.getElementById('loginBtn');

    try {
        const res = await apiRequest('/api/login', data, btn);

        localStorage.setItem('token', res.token);
        showToast('Login realizado com sucesso!', 'success');
        
        // Aguarda 1.5 segundos antes de redirecionar para o usuário ver o toast
        setTimeout(() => {
            window.location.href = '/';
        }, 1500);

    } catch (err) {
        if (err.errors) showErrors(form, err.errors);
        else showToast(err.message || 'Erro ao logar', 'error');
    }
});