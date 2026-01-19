import { showToast, showMultipleToasts } from './toast';

const API_URL = '/api';

async function apiRequest(url, method = 'POST', data = null, btn = null) {
    let spinner, text;

    if (btn) {
        spinner = btn.querySelector('.spinner-border');
        text = btn.querySelector('.btn-text');

        btn.disabled = true;
        spinner.classList.remove('d-none');
        text.classList.add('d-none');
    }

    try {
        const token = localStorage.getItem('token');

        const headers = {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        };

        if (token) {
            headers.Authorization = `Bearer ${token}`;
        }

        const response = await fetch(`${API_URL}${url}`, {
            method,
            headers,
            body: data ? JSON.stringify(data) : null,
        });

        const result = await response.json();

        if (!response.ok) throw result;

        return result;

    } finally {
        if (btn) {
            btn.disabled = false;
            spinner.classList.add('d-none');
            text.classList.remove('d-none');
        }
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
    Object.keys(errors).forEach(field => {
        const input = form.querySelector(`[name="${field}"]`);
        if (input) input.classList.add('is-invalid');
    });

    showMultipleToasts(Object.values(errors).flat(), 'error');
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
        const res = await apiRequest('/register', 'POST', data, btn);
        localStorage.setItem('token', res.token);

        showToast('Conta criada com sucesso!', 'success');
        setTimeout(() => window.location.href = '/', 1500);

    } catch (err) {
        err.errors ? showErrors(form, err.errors)
                   : showToast(err.message || 'Erro ao cadastrar', 'error');
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
        const res = await apiRequest('/login', 'POST', data, btn);
        localStorage.setItem('token', res.token);

        showToast('Login realizado com sucesso!', 'success');
        setTimeout(() => window.location.href = '/', 1500);

    } catch (err) {
        err.errors ? showErrors(form, err.errors)
                   : showToast(err.message || 'Erro ao logar', 'error');
    }
});
