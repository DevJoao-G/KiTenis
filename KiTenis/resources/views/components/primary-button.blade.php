<button
    {{ $attributes->merge([
        'type' => 'submit',
        'class' => '
            btn btn-success
            fw-semibold
            text-uppercase
            px-4 py-2
        '
    ]) }}
>
    {{ $slot }}
</button>
