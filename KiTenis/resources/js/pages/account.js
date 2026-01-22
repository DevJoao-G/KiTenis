export function initAccountPage() {
    const btnLogin = document.getElementById('btn-login');
    const btnCadastrar = document.getElementById('btn-cadastrar');

    const formLogin = document.getElementById('form-login');
    const formCadastrar = document.getElementById('form-cadastrar');

    if (!btnLogin || !btnCadastrar || !formLogin || !formCadastrar) {
        return;
    }

    const activateLogin = () => {
        btnLogin.classList.add('btn-success', 'active');
        btnLogin.classList.remove('btn-outline-success');

        btnCadastrar.classList.remove('btn-success', 'active');
        btnCadastrar.classList.add('btn-outline-success');

        formLogin.classList.remove('d-none');
        formCadastrar.classList.add('d-none');
    };

    const activateRegister = () => {
        btnCadastrar.classList.add('btn-success', 'active');
        btnCadastrar.classList.remove('btn-outline-success');

        btnLogin.classList.remove('btn-success', 'active');
        btnLogin.classList.add('btn-outline-success');

        formCadastrar.classList.remove('d-none');
        formLogin.classList.add('d-none');
    };

    btnLogin.addEventListener('click', activateLogin);
    btnCadastrar.addEventListener('click', activateRegister);
}
