@php
    $profile = [
        'name' => 'Jamie Doe',
        'email' => 'jamie@example.com',
    ];

    $currencies = [
        ['code' => 'USD', 'label' => __('US Dollar (USD)')],
        ['code' => 'EUR', 'label' => __('Euro (EUR)')],
        ['code' => 'GBP', 'label' => __('British Pound (GBP)')],
        ['code' => 'CAD', 'label' => __('Canadian Dollar (CAD)')],
        ['code' => 'AUD', 'label' => __('Australian Dollar (AUD)')],
        ['code' => 'JPY', 'label' => __('Japanese Yen (JPY)')],
    ];

    $currency = 'USD';
@endphp

<x-layout.app-layout>
    <div class="mx-auto max-w-full space-y-8">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-white">{{ __('Settings') }}</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                {{ __('Manage your profile, preferences, and account security.') }}
            </p>
        </div>

        {{-- Profile --}}
        <section
            class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm shadow-slate-900/5 dark:border-white/[0.08] dark:bg-slate-900 dark:shadow-[0_1px_0_0_rgba(255,255,255,0.04)]"
            aria-labelledby="settings-profile-heading"
        >
            <div class="border-b border-slate-200/80 px-6 py-5 dark:border-white/10">
                <h2 id="settings-profile-heading" class="text-base font-semibold text-slate-900 dark:text-white">
                    {{ __('Profile') }}
                </h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    {{ __('How you appear in Budgetlify and where we reach you.') }}
                </p>
            </div>
            <form action="#" class="divide-y divide-slate-100 dark:divide-white/10" onsubmit="return false">
                <div class="space-y-5 px-6 py-6">
                    <div>
                        <label for="settings-name" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Name') }}</label>
                        <input
                            id="settings-name"
                            name="name"
                            type="text"
                            autocomplete="name"
                            value="{{ $profile['name'] }}"
                            class="w-full rounded-xl border border-slate-200/80 bg-white px-3 py-2.5 text-sm text-slate-900 focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20 dark:border-white/[0.1] dark:bg-slate-950 dark:text-slate-100 dark:focus:ring-brand/30"
                        />
                    </div>
                    <div>
                        <label for="settings-email" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Email') }}</label>
                        <input
                            id="settings-email"
                            name="email"
                            type="email"
                            autocomplete="email"
                            value="{{ $profile['email'] }}"
                            class="w-full rounded-xl border border-slate-200/80 bg-white px-3 py-2.5 text-sm text-slate-900 focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20 dark:border-white/[0.1] dark:bg-slate-950 dark:text-slate-100 dark:focus:ring-brand/30"
                        />
                    </div>
                </div>
                <div class="flex justify-end bg-slate-50/80 px-6 py-4 dark:bg-slate-950/80">
                    <x-button type="button" variant="primary" size="md">{{ __('Save profile') }}</x-button>
                </div>
            </form>
        </section>

        {{-- Currency --}}
        <section
            class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm shadow-slate-900/5 dark:border-white/[0.08] dark:bg-slate-900 dark:shadow-[0_1px_0_0_rgba(255,255,255,0.04)]"
            aria-labelledby="settings-currency-heading"
        >
            <div class="border-b border-slate-200/80 px-6 py-5 dark:border-white/10">
                <h2 id="settings-currency-heading" class="text-base font-semibold text-slate-900 dark:text-white">
                    {{ __('Currency') }}
                </h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    {{ __('Used for amounts, budgets, and exports across your workspace.') }}
                </p>
            </div>
            <form action="#" class="divide-y divide-slate-100 dark:divide-white/10" onsubmit="return false">
                <div class="px-6 py-6">
                    <label for="settings-currency" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Display currency') }}</label>
                    <x-select id="settings-currency" name="currency">
                        @foreach ($currencies as $c)
                            <option value="{{ $c['code'] }}" @selected($currency === $c['code'])>{{ $c['label'] }}</option>
                        @endforeach
                    </x-select>
                </div>
                <div class="flex justify-end bg-slate-50/80 px-6 py-4 dark:bg-slate-950/80">
                    <x-button type="button" variant="primary" size="md">{{ __('Save currency') }}</x-button>
                </div>
            </form>
        </section>

        {{-- Theme --}}
        <section
            class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm shadow-slate-900/5 dark:border-white/[0.08] dark:bg-slate-900 dark:shadow-[0_1px_0_0_rgba(255,255,255,0.04)]"
            aria-labelledby="settings-theme-heading"
        >
            <div class="border-b border-slate-200/80 px-6 py-5 dark:border-white/10">
                <h2 id="settings-theme-heading" class="text-base font-semibold text-slate-900 dark:text-white">
                    {{ __('Appearance') }}
                </h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    {{ __('Choose light or dark. Your choice is remembered on this device.') }}
                </p>
            </div>
            <div class="divide-y divide-slate-100 px-6 py-6 dark:divide-white/10">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-900 dark:text-white">{{ __('Theme') }}</p>
                        <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400">{{ __('Switch instantly — no page reload required.') }}</p>
                    </div>
                    <div
                        class="inline-flex rounded-xl border border-slate-200/80 bg-slate-50 p-1 dark:border-white/[0.1] dark:bg-slate-950"
                        role="group"
                        aria-label="{{ __('Theme') }}"
                    >
                        <button
                            type="button"
                            data-theme-set="light"
                            class="theme-option rounded-lg px-4 py-2 text-sm font-semibold text-slate-600 transition-colors hover:text-slate-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand dark:text-slate-400 dark:hover:text-white"
                        >
                            {{ __('Light') }}
                        </button>
                        <button
                            type="button"
                            data-theme-set="dark"
                            class="theme-option rounded-lg px-4 py-2 text-sm font-semibold text-slate-600 transition-colors hover:text-slate-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand dark:text-slate-400 dark:hover:text-white"
                        >
                            {{ __('Dark') }}
                        </button>
                    </div>
                </div>
            </div>
        </section>

        {{-- Notifications --}}
        <section
            class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm shadow-slate-900/5 dark:border-white/[0.08] dark:bg-slate-900 dark:shadow-[0_1px_0_0_rgba(255,255,255,0.04)]"
            aria-labelledby="settings-notifications-heading"
        >
            <div class="border-b border-slate-200/80 px-6 py-5 dark:border-white/10">
                <h2 id="settings-notifications-heading" class="text-base font-semibold text-slate-900 dark:text-white">
                    {{ __('Notifications') }}
                </h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    {{ __('Control summaries and alerts. Demo only — preferences are not stored yet.') }}
                </p>
            </div>
            <form action="#" class="divide-y divide-slate-100 dark:divide-white/10" onsubmit="return false">
                <div class="divide-y divide-slate-100 dark:divide-white/10">
                    <label class="flex cursor-pointer items-start justify-between gap-4 px-6 py-5 transition-colors hover:bg-slate-50/80 dark:hover:bg-white/[0.04]">
                        <span class="min-w-0">
                            <span class="block text-sm font-medium text-slate-900 dark:text-white">{{ __('Daily digest email') }}</span>
                            <span class="mt-0.5 block text-sm text-slate-500 dark:text-slate-400">{{ __('A single morning summary of spending and budgets.') }}</span>
                        </span>
                        <span class="shrink-0 pt-0.5">
                            <input
                                type="checkbox"
                                name="email_digest"
                                value="1"
                                class="size-4 rounded border-slate-300 text-brand focus:ring-brand/30 dark:border-white/20 dark:bg-slate-950"
                                checked
                            />
                        </span>
                    </label>
                    <label class="flex cursor-pointer items-start justify-between gap-4 px-6 py-5 transition-colors hover:bg-slate-50/80 dark:hover:bg-white/[0.04]">
                        <span class="min-w-0">
                            <span class="block text-sm font-medium text-slate-900 dark:text-white">{{ __('Push alerts') }}</span>
                            <span class="mt-0.5 block text-sm text-slate-500 dark:text-slate-400">{{ __('Large transactions and budget threshold warnings.') }}</span>
                        </span>
                        <span class="shrink-0 pt-0.5">
                            <input
                                type="checkbox"
                                name="push_alerts"
                                value="1"
                                class="size-4 rounded border-slate-300 text-brand focus:ring-brand/30 dark:border-white/20 dark:bg-slate-950"
                            />
                        </span>
                    </label>
                    <label class="flex cursor-pointer items-start justify-between gap-4 px-6 py-5 transition-colors hover:bg-slate-50/80 dark:hover:bg-white/[0.04]">
                        <span class="min-w-0">
                            <span class="block text-sm font-medium text-slate-900 dark:text-white">{{ __('Weekly summary') }}</span>
                            <span class="mt-0.5 block text-sm text-slate-500 dark:text-slate-400">{{ __('Trends and category breakdown every Sunday.') }}</span>
                        </span>
                        <span class="shrink-0 pt-0.5">
                            <input
                                type="checkbox"
                                name="weekly_summary"
                                value="1"
                                class="size-4 rounded border-slate-300 text-brand focus:ring-brand/30 dark:border-white/20 dark:bg-slate-950"
                                checked
                            />
                        </span>
                    </label>
                </div>
                <div class="flex justify-end bg-slate-50/80 px-6 py-4 dark:bg-slate-950/80">
                    <x-button type="button" variant="primary" size="md">{{ __('Save notifications') }}</x-button>
                </div>
            </form>
        </section>

        {{-- Danger zone --}}
        <section
            class="overflow-hidden rounded-2xl border border-red-200/80 bg-white shadow-sm shadow-slate-900/5 dark:border-red-900/40 dark:bg-slate-900 dark:shadow-[0_1px_0_0_rgba(255,255,255,0.04)]"
            aria-labelledby="settings-danger-heading"
        >
            <div class="border-b border-red-100 bg-red-50/50 px-6 py-5 dark:border-red-900/30 dark:bg-red-950/20">
                <h2 id="settings-danger-heading" class="text-base font-semibold text-red-900 dark:text-red-200">
                    {{ __('Danger zone') }}
                </h2>
                <p class="mt-1 text-sm text-red-800/90 dark:text-red-300/90">
                    {{ __('Deleting your account removes access to Budgetlify. This demo will not remove any data.') }}
                </p>
            </div>
            <form action="#" class="divide-y divide-slate-100 dark:divide-white/10" onsubmit="return false">
                <div class="space-y-4 px-6 py-6">
                    <label class="flex cursor-pointer items-start gap-3">
                        <input
                            type="checkbox"
                            name="confirm_delete"
                            value="1"
                            class="mt-1 size-4 rounded border-red-300 text-red-600 focus:ring-red-500/30 dark:border-red-800 dark:bg-slate-950"
                        />
                        <span class="text-sm leading-relaxed text-slate-600 dark:text-slate-400">
                            {{ __('I understand that account deletion is permanent for a real account.') }}
                        </span>
                    </label>
                </div>
                <div class="flex justify-end bg-slate-50/80 px-6 py-4 dark:bg-slate-950/80">
                    <x-button type="button" variant="danger" size="md">{{ __('Delete account') }}</x-button>
                </div>
            </form>
        </section>
    </div>
</x-layout.app-layout>
