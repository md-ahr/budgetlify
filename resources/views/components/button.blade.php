@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
])

@php
    $variantKey = in_array($variant, ['primary', 'secondary', 'outline', 'ghost', 'danger'], true) ? $variant : 'primary';
    $sizeKey = in_array($size, ['sm', 'md', 'lg'], true) ? $size : 'md';

    $base = 'inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-lg font-semibold tracking-tight transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 dark:focus-visible:ring-offset-slate-950';

    $variants = [
        'primary' => 'bg-brand text-brand-foreground shadow-sm hover:bg-brand-dark focus-visible:ring-brand-dark',
        'secondary' => 'border border-slate-300 bg-white text-slate-800 shadow-sm hover:border-slate-400 hover:bg-slate-50 focus-visible:ring-brand dark:border-white/12 dark:bg-slate-800 dark:text-slate-100 dark:shadow-none dark:hover:border-white/18 dark:hover:bg-slate-700/80',
        'outline' => 'border-2 border-brand bg-transparent text-brand hover:bg-brand-muted focus-visible:ring-brand-dark dark:border-indigo-400/70 dark:text-indigo-300 dark:hover:bg-indigo-500/10',
        'ghost' => 'text-slate-700 hover:bg-slate-100 focus-visible:ring-brand dark:text-slate-300 dark:hover:bg-white/10',
        'danger' => 'bg-red-600 text-white shadow-sm hover:bg-red-700 focus-visible:ring-red-600 dark:bg-red-600 dark:hover:bg-red-500',
    ];

    $sizes = [
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2.5 text-sm',
        'lg' => 'px-6 py-3 text-base',
    ];

    $classes = trim($base.' '.$variants[$variantKey].' '.$sizes[$sizeKey]);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
