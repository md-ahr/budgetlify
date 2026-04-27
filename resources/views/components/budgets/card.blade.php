@props([
    'name',
    'limit',
    'spent',
])

@php
    $limit = (float) $limit;
    $spent = (float) $spent;
    $ratio = $limit > 0 ? $spent / $limit : 0.0;
    $pctDisplay = $limit > 0 ? (int) round($ratio * 100) : 0;
    $barWidth = $limit > 0 ? min(100, round($ratio * 100, 1)) : 0.0;
    $exceeded = $spent > $limit;
    $nearLimit = ! $exceeded && $pctDisplay >= 85;

    if ($exceeded) {
        $toneLabel = __('Over budget');
        $fillClass = 'fill-red-500 dark:fill-red-400';
        $textClass = 'text-red-600 dark:text-red-400';
        $badgeClass = 'bg-red-50 text-red-700 dark:bg-red-950/50 dark:text-red-300';
    } elseif ($nearLimit) {
        $toneLabel = __('Near limit');
        $fillClass = 'fill-amber-400 dark:fill-amber-400';
        $textClass = 'text-amber-700 dark:text-amber-300';
        $badgeClass = 'bg-amber-50 text-amber-800 dark:bg-amber-950/40 dark:text-amber-200';
    } else {
        $toneLabel = __('On track');
        $fillClass = 'fill-emerald-500 dark:fill-emerald-400';
        $textClass = 'text-emerald-600 dark:text-emerald-400';
        $badgeClass = 'bg-emerald-50 text-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-300';
    }
@endphp

<article {{ $attributes->class('flex flex-col rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm shadow-slate-900/5 dark:border-white/[0.08] dark:bg-slate-900 dark:shadow-[0_1px_0_0_rgba(255,255,255,0.04)]') }}>
    <div class="flex items-start justify-between gap-3">
        <div class="min-w-0">
            <h2 class="text-lg font-semibold tracking-tight text-slate-900 dark:text-white">{{ $name }}</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('Monthly budget') }}</p>
        </div>
        <span class="shrink-0 rounded-full px-2.5 py-0.5 text-xs font-medium {{ $badgeClass }}">{{ $toneLabel }}</span>
    </div>

    <dl class="mt-6 grid grid-cols-2 gap-4 text-sm">
        <div>
            <dt class="font-medium text-slate-500 dark:text-slate-400">{{ __('Limit') }}</dt>
            <dd class="mt-1 text-base font-semibold tabular-nums text-slate-900 dark:text-white">${{ number_format($limit, 2) }}</dd>
        </div>
        <div class="text-right">
            <dt class="font-medium text-slate-500 dark:text-slate-400">{{ __('Spent') }}</dt>
            <dd class="mt-1 text-base font-semibold tabular-nums {{ $textClass }}">${{ number_format($spent, 2) }}</dd>
        </div>
    </dl>

    <div class="mt-5">
        <div class="flex items-center justify-between gap-2 text-xs font-medium text-slate-500 dark:text-slate-400">
            <span>{{ __('Progress') }}</span>
            <span class="tabular-nums {{ $textClass }}">{{ $pctDisplay }}%</span>
        </div>
        <svg class="mt-2 h-2.5 w-full" viewBox="0 0 100 4" preserveAspectRatio="none" role="img" aria-label="{{ __('Spending progress') }}">
            <rect width="100" height="4" class="fill-slate-100 dark:fill-slate-800" rx="2" />
            <rect width="{{ $exceeded ? 100 : $barWidth }}" height="4" class="{{ $fillClass }}" rx="2" />
        </svg>
    </div>
</article>
