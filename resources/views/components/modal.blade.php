@props([
    'id',
    'labelledby' => null,
])

<dialog
    id="{{ $id }}"
    data-app-modal
    @if ($labelledby) aria-labelledby="{{ $labelledby }}" @endif
    aria-modal="true"
    {{ $attributes->class(
        'relative m-auto max-h-[90vh] w-[calc(100%-1.5rem)] max-w-lg overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-0 shadow-2xl shadow-slate-900/25 backdrop:bg-slate-900/60 backdrop:backdrop-blur-[2px] open:backdrop:bg-slate-900/60 dark:border-white/[0.1] dark:bg-slate-900 dark:shadow-[0_24px_48px_-12px_rgba(0,0,0,0.55)]'
    ) }}
>
    <div class="absolute right-1.5 top-1.5 z-20 sm:right-2.5 sm:top-2.5">
        <button
            type="button"
            data-close-dialog
            class="rounded-xl p-2 text-slate-500 transition-colors hover:bg-slate-100 hover:text-slate-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2 dark:text-slate-400 dark:hover:bg-white/10 dark:hover:text-slate-100 dark:focus-visible:ring-offset-slate-900"
            aria-label="{{ __('Close dialog') }}"
        >
            <svg class="size-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <div class="max-h-[85vh] overflow-y-auto overscroll-contain">
        @isset ($header)
            <div
                class="sticky top-0 z-10 border-b border-slate-100 bg-white/95 px-6 pb-4 pt-5 pr-14 backdrop-blur-sm dark:border-white/[0.08] dark:bg-slate-900/98 dark:backdrop-blur-md sm:pr-16"
            >
                {{ $header }}
            </div>
        @endisset

        {{ $slot }}
    </div>
</dialog>
