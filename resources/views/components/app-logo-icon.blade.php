@props(['class' => 'size-8'])

<img
    src="{{ asset('icon.png') }}"
    alt="Winga"
    {{ $attributes->merge(['class' => $class . ' object-contain shrink-0']) }}
/>
