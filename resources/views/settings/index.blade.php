<x-layout.app-layout>
    <div class="mx-auto max-w-full space-y-8">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-white">{{ __('Settings') }}</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                {{ __('Manage your profile, preferences, and account security.') }}
            </p>
        </div>

        @if (session('status'))
            <div
                class="rounded-xl border border-emerald-200/80 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-900 dark:border-emerald-900/40 dark:bg-emerald-950/40 dark:text-emerald-200"
                role="status"
            >
                {{ session('status') }}
            </div>
        @endif

        <div class="flex flex-col gap-6 lg:flex-row lg:items-stretch">
            {{-- Profile --}}
            <section
                class="flex min-w-0 flex-1 flex-col overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm shadow-slate-900/5 dark:border-white/[0.08] dark:bg-slate-900 dark:shadow-[0_1px_0_0_rgba(255,255,255,0.04)]"
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
                <form
                    action="{{ route('settings.profile.update') }}"
                    method="post"
                    class="flex flex-1 flex-col"
                >
                    @csrf
                    @method('PATCH')
                    <div class="flex flex-1 flex-col space-y-5 px-6 py-6">
                        <div>
                            <label for="settings-name" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Name') }}</label>
                            <input
                                id="settings-name"
                                name="name"
                                type="text"
                                autocomplete="name"
                                value="{{ old('name', $user->name) }}"
                                required
                                class="w-full rounded-xl border border-slate-200/80 bg-white px-3 py-2.5 text-sm text-slate-900 focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20 dark:border-white/[0.1] dark:bg-slate-950 dark:text-slate-100 dark:focus:ring-brand/30"
                            />
                            <x-error name="name" />
                        </div>
                        <div>
                            <label for="settings-email" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Email') }}</label>
                            <input
                                id="settings-email"
                                name="email"
                                type="email"
                                autocomplete="email"
                                value="{{ old('email', $user->email) }}"
                                required
                                class="w-full rounded-xl border border-slate-200/80 bg-white px-3 py-2.5 text-sm text-slate-900 focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20 dark:border-white/[0.1] dark:bg-slate-950 dark:text-slate-100 dark:focus:ring-brand/30"
                            />
                            <x-error name="email" />
                        </div>
                    </div>
                    <div class="mt-auto flex justify-end border-t border-slate-100 bg-slate-50/80 px-6 py-4 dark:border-white/10 dark:bg-slate-950/80">
                        <x-button type="submit" variant="primary" size="md">{{ __('Save profile') }}</x-button>
                    </div>
                </form>
            </section>

            {{-- Regional formats --}}
            <section
                class="flex min-w-0 flex-1 flex-col overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm shadow-slate-900/5 dark:border-white/[0.08] dark:bg-slate-900 dark:shadow-[0_1px_0_0_rgba(255,255,255,0.04)]"
                aria-labelledby="settings-regional-heading"
            >
                <div class="border-b border-slate-200/80 px-6 py-5 dark:border-white/10">
                    <h2 id="settings-regional-heading" class="text-base font-semibold text-slate-900 dark:text-white">
                        {{ __('Regional formats') }}
                    </h2>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        {{ __('Currency for amounts and how dates appear in lists, analytics, and budgets.') }}
                    </p>
                </div>
                <form
                    action="{{ route('settings.preferences.update') }}"
                    method="post"
                    class="flex flex-1 flex-col"
                >
                    @csrf
                    @method('PATCH')
                    <div class="flex flex-1 flex-col space-y-5 px-6 py-6">
                        <div>
                            <label for="settings-currency" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Display currency') }}</label>
                            <x-select id="settings-currency" name="currency" required>
                                @foreach ($currencies as $c)
                                    <option value="{{ $c['code'] }}" @selected(old('currency', $user->currency) === $c['code'])>{{ $c['label'] }}</option>
                                @endforeach
                            </x-select>
                            <x-error name="currency" />
                        </div>
                        <div>
                            <label for="settings-date-format" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Date format') }}</label>
                            <x-select id="settings-date-format" name="date_format" required>
                                @foreach ($dateFormats as $df)
                                    <option value="{{ $df['key'] }}" @selected(old('date_format', $user->date_format) === $df['key'])>{{ $df['label'] }}</option>
                                @endforeach
                            </x-select>
                            <x-error name="date_format" />
                        </div>
                    </div>
                    <div class="mt-auto flex justify-end border-t border-slate-100 bg-slate-50/80 px-6 py-4 dark:border-white/10 dark:bg-slate-950/80">
                        <x-button type="submit" variant="primary" size="md">{{ __('Save regional formats') }}</x-button>
                    </div>
                </form>
            </section>

            {{-- Appearance --}}
            <section
                class="flex min-w-0 flex-1 flex-col overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm shadow-slate-900/5 dark:border-white/[0.08] dark:bg-slate-900 dark:shadow-[0_1px_0_0_rgba(255,255,255,0.04)]"
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
                <div class="flex flex-1 flex-col px-6 py-6">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-slate-900 dark:text-white">{{ __('Theme') }}</p>
                            <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400">{{ __('Switch instantly — no page reload required.') }}</p>
                        </div>
                        <div
                            class="inline-flex shrink-0 rounded-xl border border-slate-200/80 bg-slate-50 p-1 dark:border-white/[0.1] dark:bg-slate-950"
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
        </div>

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
                    {{ __('Deleting your account permanently removes your profile and all associated data (transactions, budgets, and other records linked to your account).') }}
                </p>
            </div>
            <form
                action="{{ route('settings.account.destroy') }}"
                method="post"
                class="divide-y divide-slate-100 dark:divide-white/10"
            >
                @csrf
                @method('DELETE')
                <div class="space-y-4 px-6 py-6">
                    <label class="flex cursor-pointer items-start gap-3">
                        <input
                            type="checkbox"
                            name="confirm_delete"
                            value="1"
                            class="mt-1 size-4 rounded border-red-300 text-red-600 focus:ring-red-500/30 dark:border-red-800 dark:bg-slate-950"
                        />
                        <span class="text-sm leading-relaxed text-slate-600 dark:text-slate-400">
                            {{ __('I understand that this action is permanent and cannot be undone.') }}
                        </span>
                    </label>
                    @error('confirm_delete')
                        <p class="text-sm text-red-600 dark:text-red-400" role="alert">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex justify-end bg-slate-50/80 px-6 py-4 dark:bg-slate-950/80">
                    <x-button type="submit" variant="danger" size="md">{{ __('Delete account') }}</x-button>
                </div>
            </form>
        </section>
    </div>
</x-layout.app-layout>
