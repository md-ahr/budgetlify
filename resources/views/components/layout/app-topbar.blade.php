@php
    $settingsUrl = route('settings');
    $profileUrl = $settingsUrl . '#settings-profile';
@endphp

<header
    class="sticky top-0 z-30 flex h-16 shrink-0 items-center gap-4 border-b border-slate-200/80 bg-white/90 px-4 backdrop-blur-md dark:border-white/[0.07] dark:bg-slate-950/85 dark:backdrop-blur-xl sm:px-6 lg:px-8"
>
    <label
        for="app-sidebar-toggle"
        class="inline-flex cursor-pointer items-center justify-center rounded-xl p-2 text-slate-600 hover:bg-slate-100 lg:hidden dark:text-slate-400 dark:hover:bg-white/10"
    >
        <span class="sr-only">{{ __('Open menu') }}</span>
        <svg class="size-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
        </svg>
    </label>

    <div class="hidden min-w-0 flex-1 sm:block">
        <p class="truncate text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Overview') }}</p>
        <h1 class="truncate text-lg font-semibold tracking-tight text-slate-900 dark:text-white">{{ __('Dashboard') }}</h1>
    </div>

    <div class="flex min-w-0 flex-1 items-center justify-end gap-2 sm:flex-initial sm:gap-3">
        <div class="flex min-w-0 max-w-xs flex-1 items-center gap-2 lg:max-w-md">
            <div class="relative hidden min-w-0 flex-1 md:block">
                <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400" aria-hidden="true">
                    <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </span>
                <input
                    type="search"
                    name="q"
                    placeholder="{{ __('Search transactions…') }}"
                    class="w-full rounded-xl border border-slate-200/80 bg-slate-50 py-2 pl-9 pr-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20 dark:border-white/[0.1] dark:bg-slate-950 dark:text-slate-100 dark:placeholder:text-slate-500 dark:focus:ring-brand/30"
                />
            </div>

            <button
                type="button"
                class="js-app-theme-toggle shrink-0 rounded-xl p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2 dark:text-slate-400 dark:hover:bg-white/10 dark:hover:text-slate-200 dark:focus-visible:ring-offset-slate-950"
                data-label-dark="{{ __('Switch to dark mode') }}"
                data-label-light="{{ __('Switch to light mode') }}"
                aria-label="{{ __('Switch to dark mode') }}"
            >
                <span class="dark:hidden" aria-hidden="true">
                    <svg class="size-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                    </svg>
                </span>
                <span class="hidden dark:inline" aria-hidden="true">
                    <svg class="size-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
                    </svg>
                </span>
            </button>
        </div>

        <details data-topbar-dropdown class="group relative shrink-0">
            <summary
                class="relative flex cursor-pointer list-none items-center justify-center rounded-xl p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2 dark:text-slate-400 dark:hover:bg-white/10 dark:hover:text-slate-200 dark:focus-visible:ring-offset-slate-950 [&::-webkit-details-marker]:hidden"
                aria-label="{{ __('Notifications menu') }}"
            >
                <svg class="size-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.082A2.02 2.02 0 0 0 21 14.04V10a8 8 0 1 0-16 0v4.04c0 1.044.832 1.917 1.857 2.082a23.848 23.848 0 0 0 5.454 1.082c.282.038.565.057.848.057s.566-.019.848-.057Z" />
                </svg>
                <span class="absolute right-1.5 top-1.5 size-2 rounded-full bg-accent ring-2 ring-white dark:ring-slate-950" aria-hidden="true"></span>
            </summary>
            <div
                class="absolute right-0 z-50 mt-2 w-[min(100vw-2rem,20rem)] overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-lg shadow-slate-900/10 dark:border-white/10 dark:bg-slate-900 dark:shadow-black/40"
                role="region"
                aria-label="{{ __('Notifications') }}"
            >
                <div class="border-b border-slate-100 px-4 py-3 dark:border-white/10">
                    <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ __('Notifications') }}</p>
                    <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">{{ __('Demo alerts — not stored yet.') }}</p>
                </div>
                <ul class="max-h-72 divide-y divide-slate-100 overflow-y-auto dark:divide-white/10" role="list">
                    <li>
                        <a
                            href="#"
                            class="block px-4 py-3 transition-colors hover:bg-slate-50 focus:outline-none focus-visible:bg-slate-50 dark:hover:bg-white/[0.06] dark:focus-visible:bg-white/[0.06]"
                            onclick="event.preventDefault()"
                        >
                            <p class="text-sm font-medium text-slate-900 dark:text-white">{{ __('Budget nearing limit') }}</p>
                            <p class="mt-0.5 text-sm text-slate-600 dark:text-slate-400">{{ __('Groceries is at 90% of this month’s cap.') }}</p>
                            <p class="mt-1.5 text-xs text-slate-400 dark:text-slate-500">{{ __('2 hours ago') }}</p>
                        </a>
                    </li>
                    <li>
                        <a
                            href="#"
                            class="block px-4 py-3 transition-colors hover:bg-slate-50 focus:outline-none focus-visible:bg-slate-50 dark:hover:bg-white/[0.06] dark:focus-visible:bg-white/[0.06]"
                            onclick="event.preventDefault()"
                        >
                            <p class="text-sm font-medium text-slate-900 dark:text-white">{{ __('Large transaction') }}</p>
                            <p class="mt-0.5 text-sm text-slate-600 dark:text-slate-400">{{ __('$420.00 at Travel — flagged for review.') }}</p>
                            <p class="mt-1.5 text-xs text-slate-400 dark:text-slate-500">{{ __('Yesterday') }}</p>
                        </a>
                    </li>
                    <li>
                        <a
                            href="#"
                            class="block px-4 py-3 transition-colors hover:bg-slate-50 focus:outline-none focus-visible:bg-slate-50 dark:hover:bg-white/[0.06] dark:focus-visible:bg-white/[0.06]"
                            onclick="event.preventDefault()"
                        >
                            <p class="text-sm font-medium text-slate-900 dark:text-white">{{ __('Income received') }}</p>
                            <p class="mt-0.5 text-sm text-slate-600 dark:text-slate-400">{{ __('Salary deposit cleared to Checking.') }}</p>
                            <p class="mt-1.5 text-xs text-slate-400 dark:text-slate-500">{{ __('Apr 26') }}</p>
                        </a>
                    </li>
                </ul>
                <div class="border-t border-slate-100 bg-slate-50/80 px-2 py-2 dark:border-white/10 dark:bg-slate-950/80">
                    <button
                        type="button"
                        class="w-full rounded-lg px-3 py-2 text-left text-sm font-medium text-brand hover:bg-white focus:outline-none focus-visible:ring-2 focus-visible:ring-brand dark:hover:bg-white/10"
                    >
                        {{ __('Mark all as read') }}
                    </button>
                </div>
            </div>
        </details>

        <details data-topbar-dropdown class="group relative shrink-0">
            <summary
                class="flex cursor-pointer list-none items-center gap-2 rounded-xl border border-slate-200/80 bg-white py-1.5 pl-1.5 pr-2 text-left text-sm shadow-sm hover:border-slate-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2 dark:border-white/12 dark:bg-slate-900 dark:shadow-none dark:hover:border-white/18 dark:hover:bg-slate-800 dark:focus-visible:ring-offset-slate-950 sm:pr-3 [&::-webkit-details-marker]:hidden"
                aria-label="{{ __('Account menu') }}"
                aria-haspopup="menu"
            >
                <span class="flex size-8 items-center justify-center rounded-lg bg-brand-muted text-xs font-semibold text-brand dark:bg-brand/20">JD</span>
                <span class="hidden min-w-0 sm:block">
                    <span class="block truncate font-medium text-slate-900 dark:text-white">{{ __('Jamie Doe') }}</span>
                    <span class="block truncate text-xs text-slate-500 dark:text-slate-400">{{ __('Pro plan') }}</span>
                </span>
                <svg
                    class="ml-0.5 hidden size-4 shrink-0 text-slate-400 transition-transform group-open:rotate-180 sm:block dark:text-slate-500"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="2"
                    stroke="currentColor"
                    aria-hidden="true"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                </svg>
            </summary>
            <div
                class="absolute right-0 z-50 mt-2 w-56 overflow-hidden rounded-xl border border-slate-200/80 bg-white py-1 shadow-lg shadow-slate-900/10 dark:border-white/10 dark:bg-slate-900 dark:shadow-black/40"
                role="menu"
                aria-label="{{ __('Account') }}"
            >
                <a
                    href="{{ $profileUrl }}"
                    class="block px-4 py-2.5 text-sm text-slate-700 transition-colors hover:bg-slate-50 focus:outline-none focus-visible:bg-slate-50 dark:text-slate-200 dark:hover:bg-white/[0.06] dark:focus-visible:bg-white/[0.06]"
                    role="menuitem"
                >
                    {{ __('Profile') }}
                </a>
                <a
                    href="{{ $settingsUrl }}"
                    class="block px-4 py-2.5 text-sm text-slate-700 transition-colors hover:bg-slate-50 focus:outline-none focus-visible:bg-slate-50 dark:text-slate-200 dark:hover:bg-white/[0.06] dark:focus-visible:bg-white/[0.06]"
                    role="menuitem"
                >
                    {{ __('Settings') }}
                </a>
                <div class="my-1 h-px bg-slate-100 dark:bg-white/10" role="separator"></div>
                <a
                    href="#"
                    class="block px-4 py-2.5 text-sm font-medium text-red-600 transition-colors hover:bg-red-50 focus:outline-none focus-visible:bg-red-50 dark:text-red-400 dark:hover:bg-red-950/40 dark:focus-visible:bg-red-950/40"
                    role="menuitem"
                    onclick="event.preventDefault()"
                >
                    {{ __('Log out') }}
                </a>
            </div>
        </details>
    </div>
</header>
