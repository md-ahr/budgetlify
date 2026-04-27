@php
    $nav = [
        ['label' => __('Dashboard'), 'href' => route('dashboard'), 'route' => 'dashboard', 'icon' => 'home'],
        ['label' => __('Transactions'), 'href' => route('transactions'), 'route' => 'transactions', 'icon' => 'transactions'],
        ['label' => __('Budgets'), 'href' => route('budgets'), 'route' => 'budgets', 'icon' => 'budgets'],
        ['label' => __('Analytics'), 'href' => route('analytics'), 'route' => 'analytics', 'icon' => 'analytics'],
        ['label' => __('Settings'), 'href' => route('settings'), 'route' => 'settings', 'icon' => 'settings'],
    ];
@endphp

<aside
    id="app-sidebar"
    class="fixed inset-y-0 left-0 z-40 flex w-64 -translate-x-full flex-col border-r border-slate-200/80 bg-white shadow-xl shadow-slate-900/5 transition-transform duration-200 ease-out peer-checked:translate-x-0 dark:border-white/[0.07] dark:bg-slate-900 dark:shadow-[4px_0_24px_-8px_rgba(0,0,0,0.5)] lg:translate-x-0 lg:shadow-none"
    aria-label="{{ __('Application') }}"
>
    <div class="flex h-16 shrink-0 items-center justify-between gap-3 border-b border-slate-200/80 px-4 dark:border-white/[0.07] lg:h-[4.25rem] lg:px-5">
        <a
            href="{{ route('dashboard') }}"
            class="flex min-w-0 flex-1 items-center gap-2 rounded-lg py-1 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2 dark:focus-visible:ring-offset-slate-950"
        >
            <img
                src="{{ asset('images/logo-budgetlify.webp') }}"
                width="1536"
                height="1024"
                alt="{{ config('app.name', 'Budgetlify') }}"
                class="h-22.5 w-auto object-cover dark:invert-100 dark:brightness-110 dark:contrast-95"
                decoding="async"
            />
        </a>
        <label
            for="app-sidebar-toggle"
            class="cursor-pointer rounded-lg p-2 text-slate-500 transition-colors hover:bg-slate-100 hover:text-slate-800 lg:hidden dark:text-slate-400 dark:hover:bg-white/10 dark:hover:text-slate-100"
        >
            <span class="sr-only">{{ __('Close menu') }}</span>
            <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </label>
    </div>

    <nav class="flex flex-1 flex-col gap-0.5 overflow-y-auto px-3 py-4" aria-label="{{ __('Main navigation') }}">
        @foreach ($nav as $item)
            @php
                $active = isset($item['route']) && request()->routeIs($item['route']);
            @endphp
            <a
                href="{{ $item['href'] }}"
                @if ($active) aria-current="page" @endif
                class="{{ $active
                    ? 'bg-brand-muted/90 text-brand shadow-sm shadow-brand/5 dark:bg-brand/15 dark:text-brand dark:shadow-none'
                    : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-white/[0.06] dark:hover:text-slate-100' }} group relative flex min-h-[2.75rem] items-center gap-3 rounded-xl py-2.5 pl-4 pr-3 text-sm font-medium transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2 dark:focus-visible:ring-offset-slate-950"
            >
                @if ($active)
                    <span
                        class="absolute left-0 top-1/2 h-7 w-1 -translate-y-1/2 rounded-r-full bg-brand shadow-[2px_0_12px_-2px_rgba(79,70,229,0.5)] dark:shadow-[2px_0_12px_-2px_rgba(129,140,248,0.45)]"
                        aria-hidden="true"
                    ></span>
                @endif
                @switch($item['icon'])
                    @case('home')
                        <svg
                            class="{{ $active ? 'text-brand dark:text-brand' : 'text-slate-400 transition-colors group-hover:text-slate-600 dark:text-slate-500 dark:group-hover:text-slate-300' }} size-5 shrink-0"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            aria-hidden="true"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                        @break
                    @case('analytics')
                        <svg
                            class="{{ $active ? 'text-brand dark:text-brand' : 'text-slate-400 transition-colors group-hover:text-slate-600 dark:text-slate-500 dark:group-hover:text-slate-300' }} size-5 shrink-0"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            aria-hidden="true"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                        </svg>
                        @break
                    @case('transactions')
                        <svg
                            class="{{ $active ? 'text-brand dark:text-brand' : 'text-slate-400 transition-colors group-hover:text-slate-600 dark:text-slate-500 dark:group-hover:text-slate-300' }} size-5 shrink-0"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            aria-hidden="true"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                        </svg>
                        @break
                    @case('budgets')
                        <svg
                            class="{{ $active ? 'text-brand dark:text-brand' : 'text-slate-400 transition-colors group-hover:text-slate-600 dark:text-slate-500 dark:group-hover:text-slate-300' }} size-5 shrink-0"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            aria-hidden="true"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                        </svg>
                        @break
                    @case('settings')
                        <svg
                            class="{{ $active ? 'text-brand dark:text-brand' : 'text-slate-400 transition-colors group-hover:text-slate-600 dark:text-slate-500 dark:group-hover:text-slate-300' }} size-5 shrink-0"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            aria-hidden="true"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 0 1 1.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.506.71.93.78l.893.15c.543.09.94.56.94 1.109v1.094c0 .55-.397 1.02-.94 1.109l-.894.149c-.424.07-.764.383-.929.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 0 1-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.398.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 0 1-.12-1.45l.527-.737c.25-.35.272-.806.107-1.204-.165-.397-.506-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.109l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.107-1.204l-.527-.738a1.125 1.125 0 0 1 .12-1.45l.773-.773a1.125 1.125 0 0 1 1.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        @break
                    @default
                        <svg
                            class="size-5 shrink-0 text-slate-400 dark:text-slate-500"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            aria-hidden="true"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                        </svg>
                @endswitch
                <span class="min-w-0 truncate">{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>

    <div class="mt-auto border-t border-slate-200/80 px-4 py-4 dark:border-white/[0.07]">
        <p class="truncate text-xs text-slate-500 dark:text-slate-400">{{ config('app.name', 'Budgetlify') }}</p>
        <p class="text-xs font-medium text-slate-700 dark:text-slate-300">{{ __('Personal workspace') }}</p>
    </div>
</aside>
