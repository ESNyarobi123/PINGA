@props([
    'name' => 'star-20',
    'size' => 20,
])

@php
    $iconId = str_contains($name, ':') ? $name : 'fluent-color:'.$name;
@endphp
<iconify-icon
    icon="{{ $iconId }}"
    width="{{ $size }}"
    height="{{ $size }}"
    {{ $attributes->class('inline-block shrink-0 align-middle [--iconify-icon-width:1em] [--iconify-icon-height:1em]') }}
></iconify-icon>
