@props([
    'title',
    'description',
    'showClearFilters' => false,
])

<div class="flex flex-col items-center justify-center rounded-2xl border border-dashed border-slate-200/80 bg-slate-50/50 px-6 py-16 text-center dark:border-white/[0.12] dark:bg-slate-900/80 sm:px-10">
    <div class="flex size-14 items-center justify-center rounded-2xl bg-white shadow-sm shadow-slate-900/5 ring-1 ring-slate-200/80 dark:bg-slate-800 dark:ring-white/[0.08]">
        <svg class="size-7 text-slate-400 dark:text-slate-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
        </svg>
    </div>
    <h2 class="mt-6 text-lg font-semibold tracking-tight text-slate-900 dark:text-white">{{ $title }}</h2>
    <p class="mt-2 max-w-md text-sm leading-relaxed text-slate-500 dark:text-slate-400">{{ $description }}</p>
    <div class="mt-8 flex flex-col items-stretch gap-3 sm:flex-row sm:items-center">
        <x-button type="button" variant="primary" size="md" class="w-full sm:w-auto" data-open-transaction-modal="true">
            {{ __('Add transaction') }}
        </x-button>
        @if ($showClearFilters)
            <x-button href="{{ route('transactions') }}" variant="secondary" size="md" class="w-full sm:w-auto">
                {{ __('Clear filters') }}
            </x-button>
        @endif
    </div>
</div>
