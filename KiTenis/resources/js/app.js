import './bootstrap';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';




document.addEventListener('DOMContentLoaded', () => {
    const btnLogin = document.getElementById('btn-login');
    const btnCadastrar = document.getElementById('btn-cadastrar');

    const formLogin = document.getElementById('form-login');
    const formCadastrar = document.getElementById('form-cadastrar');

    if (!btnLogin || !btnCadastrar) return;

    btnLogin.addEventListener('click', () => {
        btnLogin.classList.add('btn-success', 'active');
        btnLogin.classList.remove('btn-outline-success');

        btnCadastrar.classList.remove('btn-success', 'active');
        btnCadastrar.classList.add('btn-outline-success');

        formLogin.classList.remove('d-none');
        formCadastrar.classList.add('d-none');
    });

    btnCadastrar.addEventListener('click', () => {
        btnCadastrar.classList.add('btn-success', 'active');
        btnCadastrar.classList.remove('btn-outline-success');

        btnLogin.classList.remove('btn-success', 'active');
        btnLogin.classList.add('btn-outline-success');

        formCadastrar.classList.remove('d-none');
        formLogin.classList.add('d-none');
    });
});

