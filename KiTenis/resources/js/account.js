document.addEventListener('DOMContentLoaded', function() {
    const btnLogin = document.getElementById('btn-login');
    const btnCadastrar = document.getElementById('btn-cadastrar');
    const formLogin = document.getElementById('form-login');
    const formCadastrar = document.getElementById('form-cadastrar');
    
    let activeForm = 'login';
    let isAnimating = false; // Previne cliques durante animação

    function switchForm(targetForm) {
        if (activeForm === targetForm || isAnimating) return;

        isAnimating = true;

        const currentFormElement = activeForm === 'login' ? formLogin : formCadastrar;
        const targetFormElement = targetForm === 'login' ? formLogin : formCadastrar;
        const currentBtn = activeForm === 'login' ? btnLogin : btnCadastrar;
        const targetBtn = targetForm === 'login' ? btnLogin : btnCadastrar;

        // Inicia animação de saída do formulário atual
        currentFormElement.classList.add('exiting');
        currentFormElement.classList.remove('active');

        // Aguarda a animação de saída completar
        setTimeout(() => {
            // Remove classes do formulário que saiu
            currentFormElement.classList.remove('exiting');
            
            // Inicia animação de entrada do novo formulário
            targetFormElement.classList.add('active');
            
            // Atualiza os botões
            currentBtn.classList.remove('btn-secondary', 'active');
            currentBtn.classList.add('btn-light');
            
            targetBtn.classList.remove('btn-light');
            targetBtn.classList.add('btn-secondary', 'active');
            
            activeForm = targetForm;

            // Libera para nova animação após conclusão
            setTimeout(() => {
                isAnimating = false;
            }, 600);
        }, 300);
    }

    // Event listeners para os botões
    btnLogin.addEventListener('click', () => switchForm('login'));
    btnCadastrar.addEventListener('click', () => switchForm('cadastrar'));
});