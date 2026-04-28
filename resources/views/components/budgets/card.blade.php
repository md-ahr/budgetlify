@props([
    'budget',
    'spent',
    'spentPeriodLabel',
])

@php
    /** @var \App\Models\Budget $budget */
    $name = $budget->name;
    $limit = (float) $budget->monthly_limit;
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
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                {{ __('Category: :cat', ['cat' => $budget->category]) }}
            </p>
            <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-500">
                {{ __('Spent from :type · :period', ['type' => __('expenses'), 'period' => $spentPeriodLabel]) }}
            </p>
        </div>
        <span class="shrink-0 rounded-full px-2.5 py-0.5 text-xs font-medium {{ $badgeClass }}">{{ $toneLabel }}</span>
    </div>

    <dl class="mt-6 grid grid-cols-2 gap-4 text-sm">
        <div>
            <dt class="font-medium text-slate-500 dark:text-slate-400">{{ __('Limit') }}</dt>
            <dd class="mt-1 text-base font-semibold tabular-nums text-slate-900 dark:text-white">{{ $money($limit) }}</dd>
        </div>
        <div class="text-right">
            <dt class="font-medium text-slate-500 dark:text-slate-400">{{ __('Spent') }}</dt>
            <dd class="mt-1 text-base font-semibold tabular-nums {{ $textClass }}">{{ $money($spent) }}</dd>
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

    <div class="mt-6 flex items-center justify-end gap-1 border-t border-slate-100 pt-5 dark:border-white/10">
        <button
            type="button"
            data-edit-budget="{{ json_encode(array_merge($budget->only(['id', 'name', 'category']), ['monthly_limit' => \App\Support\Money::toDisplayAmount((float) $budget->monthly_limit)]), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) }}"
            class="inline-flex size-9 items-center justify-center rounded-xl text-slate-500 transition-colors hover:bg-slate-100 hover:text-slate-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2 dark:text-slate-400 dark:hover:bg-white/10 dark:hover:text-white dark:focus-visible:ring-offset-slate-900"
            aria-label="{{ __('Edit budget') }}"
        >
            <svg class="size-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
            </svg>
        </button>
        <form
            method="post"
            action="{{ route('budgets.destroy', $budget) }}"
            class="inline"
            onsubmit="return confirm({{ json_encode(__('Are you sure you want to delete this budget?')) }})"
        >
            @csrf
            @method('DELETE')
            <button
                type="submit"
                class="inline-flex size-9 cursor-pointer items-center justify-center rounded-xl text-slate-500 transition-colors hover:bg-red-50 hover:text-red-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2 dark:text-slate-400 dark:hover:bg-red-950/40 dark:hover:text-red-400 dark:focus-visible:ring-offset-slate-900"
                aria-label="{{ __('Delete budget') }}"
            >
                <svg class="size-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                </svg>
            </button>
        </form>
    </div>
</article>
