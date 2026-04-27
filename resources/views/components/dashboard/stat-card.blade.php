@props([
    'label',
    'hint' => null,
])

<div {{ $attributes->class('rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm shadow-slate-900/5 dark:border-white/[0.08] dark:bg-slate-900 dark:shadow-[0_1px_0_0_rgba(255,255,255,0.04)]') }}>
    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ $label }}</p>
    <div class="mt-2 text-2xl font-semibold tracking-tight text-slate-900 tabular-nums dark:text-white">
        {{ $slot }}
    </div>
    @if ($hint)
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $hint }}</p>
    @endif
</div>
