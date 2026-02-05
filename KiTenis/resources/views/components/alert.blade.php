@props(['type' => 'info', 'message', 'delay' => 3000])

@php
    $toastType = match($type) {
        'success' => 'success',
        'danger'  => 'danger',
        'error'   => 'danger',
        'warning' => 'warning',
        default   => 'info',
    };

    $icon = match($toastType) {
        'success' => 'bi-check-circle',
        'danger'  => 'bi-exclamation-triangle',
        'warning' => 'bi-info-circle',
        default   => 'bi-info-circle',
    };

    $toastId = 'toast_' . \Illuminate\Support\Str::random(10);
@endphp

<div id="{{ $toastId }}"
     class="toast align-items-center text-bg-{{ $toastType }} border-0"
     role="alert"
     aria-live="assertive"
     aria-atomic="true"
     data-bs-delay="{{ (int) $delay }}">
    <div class="d-flex">
        <div class="toast-body">
            <i class="bi {{ $icon }} me-2"></i>
            {{ $message }}
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Fechar"></button>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const toastEl = document.getElementById(@json($toastId));
    if (!toastEl) return;

    // garante um container único (layout deve ter, mas fica resiliente)
    let container = document.getElementById('appToastContainer');
    if (!container) {
        container = document.createElement('div');
        container.id = 'appToastContainer';
        container.className = 'toast-container position-fixed top-0 end-0 p-3';
        container.style.zIndex = '2000';
        document.body.appendChild(container);
    }

    container.appendChild(toastEl);

    const bs = window.bootstrap;
    if (!bs || !bs.Toast) return;

    const delayAttr = toastEl.getAttribute('data-bs-delay');
    const delay = delayAttr ? parseInt(delayAttr, 10) : 3000;

    const t = bs.Toast.getOrCreateInstance(toastEl, { autohide: true, delay: delay });
    t.show();

    toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
});
</script>
@endpush
