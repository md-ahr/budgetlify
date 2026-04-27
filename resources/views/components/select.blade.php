@props([])

@php
    $selectClasses = 'w-full cursor-pointer appearance-none rounded-xl border border-slate-200/80 bg-slate-50 py-2.5 pl-3 pr-10 text-sm font-medium text-slate-900 shadow-sm transition-colors hover:border-slate-300/90 focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20 dark:border-white/[0.1] dark:bg-slate-950 dark:text-slate-100 dark:hover:border-white/[0.14] dark:focus:ring-brand/30';
@endphp

<div class="relative">
    <select {{ $attributes->class($selectClasses) }}>
        {{ $slot }}
    </select>
    <span class="pointer-events-none absolute inset-y-0 right-2.5 flex items-center text-slate-400 dark:text-slate-500" aria-hidden="true">
        <svg class="size-4 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
        </svg>
    </span>
</div>
