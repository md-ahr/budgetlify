@php
    $labelClass = 'mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400';
    $inputClass = 'h-11 w-full rounded-xl border border-slate-200/90 bg-white px-3.5 text-sm text-slate-900 shadow-[0_1px_2px_rgba(15,23,42,0.04)] outline-none ring-brand/0 transition-[border-color,box-shadow,ring] placeholder:text-slate-400 focus:border-brand focus:ring-4 focus:ring-brand/15 dark:border-white/[0.12] dark:bg-slate-950 dark:text-slate-100 dark:shadow-none dark:placeholder:text-slate-500 dark:focus:border-indigo-400/60 dark:focus:ring-indigo-500/20';
@endphp

<x-layout.auth-layout :pageTitle="__('Log in')">
    <div
        class="relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white/90 p-8 shadow-[0_20px_50px_-12px_rgba(15,23,42,0.12),0_0_0_1px_rgba(15,23,42,0.04)] backdrop-blur-xl dark:border-white/[0.09] dark:bg-slate-900/90 dark:shadow-[0_24px_60px_-12px_rgba(0,0,0,0.45),0_0_0_1px_rgba(255,255,255,0.05)] sm:p-10"
    >
        <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-brand/50 to-transparent dark:via-indigo-400/40" aria-hidden="true"></div>

        <p class="text-[0.6875rem] font-semibold uppercase tracking-[0.2em] text-brand dark:text-indigo-400">
            {{ __('Sign in') }}
        </p>
        <h1 class="mt-3 text-2xl font-semibold tracking-tight text-slate-900 dark:text-white sm:text-[1.625rem] sm:leading-tight">
            {{ __('Welcome back') }}
        </h1>
        <p class="mt-2 text-sm leading-relaxed text-slate-600 dark:text-slate-400">
            {{ __('Sign in to continue to your workspace.') }}
        </p>

        <form action="#" method="post" class="mt-9 space-y-6" onsubmit="return false">
            @csrf

            <div class="space-y-2">
                <label for="login-email" class="{{ $labelClass }}">{{ __('Email') }}</label>
                <input
                    id="login-email"
                    name="email"
                    type="email"
                    autocomplete="email"
                    required
                    placeholder="you@example.com"
                    class="{{ $inputClass }}"
                />
            </div>

            <div class="space-y-2">
                <div class="flex items-end justify-between gap-3">
                    <label for="login-password" class="{{ $labelClass }} !mb-0">{{ __('Password') }}</label>
                    <a
                        href="{{ route('password.request') }}"
                        class="max-w-[11rem] shrink-0 text-right text-xs font-semibold leading-tight text-brand transition-colors hover:text-brand-dark focus:outline-none focus-visible:underline dark:text-indigo-400 dark:hover:text-indigo-300"
                    >
                        {{ __('Forgot password?') }}
                    </a>
                </div>
                <input
                    id="login-password"
                    name="password"
                    type="password"
                    autocomplete="current-password"
                    required
                    class="{{ $inputClass }}"
                />
            </div>

            <x-button type="submit" variant="primary" size="lg" class="mt-2 w-full rounded-xl py-3 text-[0.9375rem] shadow-md shadow-brand/20">
                {{ __('Log in') }}
            </x-button>
        </form>

        <div class="mt-8 border-t border-slate-100 pt-8 dark:border-white/[0.08]">
            <p class="text-center text-sm text-slate-600 dark:text-slate-400">
                {{ __('Don’t have an account?') }}
                <a
                    href="{{ route('register') }}"
                    class="font-semibold text-brand transition-colors hover:text-brand-dark focus:outline-none focus-visible:underline dark:text-indigo-400 dark:hover:text-indigo-300"
                >
                    {{ __('Create one') }}
                </a>
            </p>
        </div>
    </div>
</x-layout.auth-layout>
