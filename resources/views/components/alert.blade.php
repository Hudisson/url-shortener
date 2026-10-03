@props(['type' => 'error'])

<div {{ $attributes->class([$type])->merge(['role' => 'alert']) }}>
    <span>{{ $slot }}</span>

    <button type="button" class="alert-close" data-dismiss-alert aria-label="Fechar aviso">
        <i class="fa-solid fa-xmark" aria-hidden="true"></i>
    </button>
</div>
