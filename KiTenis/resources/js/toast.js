/**
 * Container para os toasts (canto inferior direito)
 */
function getToastContainer() {
    let container = document.getElementById('toast-container');
    
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        container.style.zIndex = '9999';
        document.body.appendChild(container);
    }
    
    return container;
}

/**
 * Cria um toast individual
 */
function createToast(message, type = 'info') {
    const toastId = `toast-${Date.now()}-${Math.random().toString(36).substr(2, 9)}`;
    
    // Define cores e ícones por tipo
    const config = {
        success: {
            bgClass: 'bg-success',
            icon: 'bi-check-circle-fill',
            title: 'Sucesso'
        },
        error: {
            bgClass: 'bg-danger',
            icon: 'bi-x-circle-fill',
            title: 'Erro'
        },
        warning: {
            bgClass: 'bg-warning',
            icon: 'bi-exclamation-triangle-fill',
            title: 'Atenção'
        },
        info: {
            bgClass: 'bg-info',
            icon: 'bi-info-circle-fill',
            title: 'Informação'
        }
    };
    
    const { bgClass, icon, title } = config[type] || config.info;
    
    const toastHTML = `
        <div id="${toastId}" class="toast align-items-center text-white ${bgClass} border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
            <div class="d-flex">
                <div class="toast-body d-flex align-items-start gap-2">
                    <i class="bi ${icon} fs-5 mt-1"></i>
                    <div class="flex-grow-1">
                        <strong class="d-block mb-1">${title}</strong>
                        <span style="white-space: pre-line;">${message}</span>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;
    
    return { toastId, toastHTML };
}

/**
 * Exibe um toast
 */
export function showToast(message, type = 'info') {
    const container = getToastContainer();
    const { toastId, toastHTML } = createToast(message, type);
    
    // Adiciona o toast ao container
    container.insertAdjacentHTML('beforeend', toastHTML);
    
    // Inicializa o toast do Bootstrap
    const toastElement = document.getElementById(toastId);
    const bsToast = new bootstrap.Toast(toastElement);
    
    // Remove o toast do DOM quando for fechado
    toastElement.addEventListener('hidden.bs.toast', () => {
        toastElement.remove();
    });
    
    // Exibe o toast
    bsToast.show();
}

/**
 * Exibe múltiplos toasts (um para cada erro)
 */
export function showMultipleToasts(messages, type = 'error') {
    if (Array.isArray(messages)) {
        messages.forEach((message, index) => {
            // Adiciona delay progressivo para empilhar visualmente
            setTimeout(() => {
                showToast(message, type);
            }, index * 100);
        });
    } else {
        showToast(messages, type);
    }
}