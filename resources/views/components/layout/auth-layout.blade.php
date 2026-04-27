@props([
    'pageTitle' => null,
])

@php
    $documentTitle = $pageTitle
        ? $pageTitle.' — '.config('app.name', 'Budgetlify')
        : config('app.name', 'Budgetlify');
@endphp

<x-layout.head :title="$documentTitle" />

<div class="relative flex min-h-screen flex-col overflow-hidden bg-slate-100 lg:flex-row dark:bg-slate-950">
    {{-- Branding (desktop split) --}}
    <div
        class="relative hidden min-h-0 flex-col justify-between overflow-hidden bg-[#0c1222] px-10 py-12 text-white lg:flex lg:w-[44%] xl:w-[42%] xl:px-14 xl:py-16"
    >
        <div
            class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_100%_90%_at_0%_0%,rgba(79,70,229,0.35),transparent_58%),radial-gradient(ellipse_80%_60%_at_100%_100%,rgba(34,197,94,0.14),transparent_52%),radial-gradient(ellipse_50%_40%_at_80%_20%,rgba(129,140,248,0.15),transparent_45%)]"
        ></div>
        <div
            class="pointer-events-none absolute inset-0 opacity-[0.35] dark:opacity-[0.25]"
            style="background-image: linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px); background-size: 48px 48px;"
        ></div>
        <div class="pointer-events-none absolute -right-24 top-1/4 size-72 rounded-full bg-brand/20 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-16 left-1/4 size-56 rounded-full bg-accent/10 blur-3xl"></div>

        <div class="relative z-10">
            <a
                href="{{ route('dashboard') }}"
                class="inline-flex items-center gap-3 rounded-xl outline-none ring-offset-2 ring-offset-[#0c1222] transition-opacity hover:opacity-90 focus-visible:ring-2 focus-visible:ring-white/35"
                aria-label="{{ config('app.name', 'Budgetlify') }} — {{ __('Home') }}"
            >
                <img
                    src="{{ asset('images/logo-budgetlify.webp') }}"
                    width="1536"
                    height="1024"
                    alt="logo"
                    class="h-25 w-auto invert-100"
                    decoding="async"
                />
            </a>
            <p class="mt-12 max-w-[17.5rem] text-[1.65rem] font-semibold leading-snug tracking-tight text-white xl:max-w-sm xl:text-[1.85rem] xl:leading-tight">
                {{ __('Clarity for every dollar.') }}
            </p>
            <p class="mt-5 max-w-sm text-[0.9375rem] leading-relaxed text-slate-400">
                {{ __('Budgetlify helps you see spending, budgets, and cash flow in one calm workspace, built for everyday money decisions.') }}
            </p>
        </div>

        <div class="relative z-10 space-y-5">
            <div class="flex flex-wrap gap-2">
                <span class="rounded-full border border-white/10 bg-white/[0.06] px-3.5 py-1.5 text-xs font-medium text-slate-300 backdrop-blur-sm">{{ __('Bank-grade security') }}</span>
                <span class="rounded-full border border-white/10 bg-white/[0.06] px-3.5 py-1.5 text-xs font-medium text-slate-300 backdrop-blur-sm">{{ __('Real-time insights') }}</span>
                <span class="rounded-full border border-white/10 bg-white/[0.06] px-3.5 py-1.5 text-xs font-medium text-slate-300 backdrop-blur-sm">{{ __('No spreadsheet fatigue') }}</span>
            </div>
            <p class="text-xs font-medium text-slate-500">
                © {{ now()->year }} {{ config('app.name', 'Budgetlify') }}
            </p>
        </div>
    </div>

    {{-- Form column --}}
    <div class="relative flex min-h-screen flex-1 flex-col">
        <div
            class="pointer-events-none absolute inset-0 bg-slate-100 dark:bg-slate-950"
            aria-hidden="true"
        ></div>
        <div
            class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_85%_55%_at_50%_-10%,rgba(79,70,229,0.09),transparent_55%),radial-gradient(ellipse_70%_45%_at_100%_100%,rgba(34,197,94,0.05),transparent_50%)] dark:bg-[radial-gradient(ellipse_85%_55%_at_50%_-10%,rgba(79,70,229,0.12),transparent_55%),radial-gradient(ellipse_70%_45%_at_100%_100%,rgba(34,197,94,0.06),transparent_50%)]"
            aria-hidden="true"
        ></div>
        <div class="pointer-events-none absolute right-0 top-0 size-[min(55vw,28rem)] translate-x-1/3 -translate-y-1/4 rounded-full bg-brand/[0.07] blur-3xl dark:bg-brand/15" aria-hidden="true"></div>

        <header class="relative z-10 flex w-full items-center justify-end gap-3 px-4 py-5 sm:px-8 sm:py-6">
            <a
                href="{{ route('dashboard') }}"
                class="mr-auto inline-flex items-center gap-2 rounded-xl py-1 text-sm font-medium text-slate-600 transition-colors hover:text-slate-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2 dark:text-slate-400 dark:hover:text-white dark:focus-visible:ring-offset-slate-950 lg:hidden"
            >
                <img
                    src="{{ asset('images/logo-budgetlify.webp') }}"
                    width="1536"
                    height="1024"
                    alt="{{ config('app.name', 'Budgetlify') }}"
                    class="h-8 w-auto dark:invert-100 dark:brightness-110 dark:contrast-95"
                    decoding="async"
                />
            </a>
            <button
                type="button"
                class="js-app-theme-toggle inline-flex size-10 shrink-0 items-center justify-center rounded-full border border-slate-200/90 bg-white/90 text-slate-500 shadow-sm backdrop-blur-sm transition-colors hover:border-slate-300 hover:bg-white hover:text-slate-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2 dark:border-white/10 dark:bg-slate-900/90 dark:text-slate-400 dark:hover:border-white/15 dark:hover:bg-slate-800 dark:hover:text-slate-200 dark:focus-visible:ring-offset-slate-950"
                data-label-dark="{{ __('Switch to dark mode') }}"
                data-label-light="{{ __('Switch to light mode') }}"
                aria-label="{{ __('Switch to dark mode') }}"
            >
                <span class="dark:hidden" aria-hidden="true">
                    <svg class="size-[1.125rem]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                    </svg>
                </span>
                <span class="hidden dark:inline" aria-hidden="true">
                    <svg class="size-[1.125rem]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
                    </svg>
                </span>
            </button>
        </header>

        <main class="relative z-10 flex flex-1 flex-col items-center justify-center px-4 pb-16 pt-2 sm:px-8">
            <div class="w-full max-w-125">
                {{ $slot }}
            </div>
        </main>
    </div>
</div>

<x-layout.app-close />
