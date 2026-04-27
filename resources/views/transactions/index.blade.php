@php
    $categories = ['Bills', 'Dining', 'Groceries', 'Income', 'Salary', 'Shopping', 'Transport'];

    $transactions = [
        ['date' => '2026-04-27', 'title' => 'Whole Foods', 'category' => 'Groceries', 'amount' => 84.2, 'type' => 'expense'],
        ['date' => '2026-04-27', 'title' => 'Coffee Lab', 'category' => 'Dining', 'amount' => 6.75, 'type' => 'expense'],
        ['date' => '2026-04-26', 'title' => 'Salary deposit', 'category' => 'Salary', 'amount' => 4120.0, 'type' => 'income'],
        ['date' => '2026-04-26', 'title' => 'City Utilities', 'category' => 'Bills', 'amount' => 132.5, 'type' => 'expense'],
        ['date' => '2026-04-25', 'title' => 'Ride share', 'category' => 'Transport', 'amount' => 18.4, 'type' => 'expense'],
        ['date' => '2026-04-24', 'title' => 'Bookstore', 'category' => 'Shopping', 'amount' => 42.99, 'type' => 'expense'],
        ['date' => '2026-04-23', 'title' => 'Freelance payout', 'category' => 'Income', 'amount' => 650.0, 'type' => 'income'],
        ['date' => '2026-04-22', 'title' => 'Pharmacy', 'category' => 'Shopping', 'amount' => 28.1, 'type' => 'expense'],
    ];

    $txTotal = count($transactions);
    $demoPerPage = 10;
    $demoShowingTo = min($demoPerPage, $txTotal);
    $demoPageCount = max(1, (int) ceil($txTotal / $demoPerPage));
@endphp

<x-layout.app-layout>
    <div class="mx-auto max-w-full space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-white">{{ __('Transactions') }}</h1>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('Review and filter your activity across accounts.') }}</p>
            </div>
            <x-button type="button" variant="primary" size="md" class="w-full shrink-0 sm:w-auto" data-open-transaction-modal="true">
                {{ __('Add transaction') }}
            </x-button>
        </div>

        <form
            action="#"
            class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm shadow-slate-900/5 dark:border-white/[0.08] dark:bg-slate-900 dark:shadow-[0_1px_0_0_rgba(255,255,255,0.04)] sm:p-6"
            onsubmit="return false"
        >
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-12 lg:items-end lg:gap-x-4 lg:gap-y-5">
                <div class="md:col-span-2 lg:col-span-5">
                    <label for="tx-search" class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ __('Search') }}</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400 dark:text-slate-500" aria-hidden="true">
                            <svg class="size-4 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                        </span>
                        <input
                            id="tx-search"
                            type="search"
                            name="search"
                            value=""
                            placeholder="{{ __('Search by title…') }}"
                            class="w-full rounded-xl border border-slate-200/80 bg-slate-50 py-2.5 pl-9 pr-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 transition-colors hover:border-slate-300/90 focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20 dark:border-white/[0.1] dark:bg-slate-950 dark:text-slate-100 dark:placeholder:text-slate-500 dark:hover:border-white/[0.14] dark:focus:ring-brand/30"
                        />
                    </div>
                </div>

                <div class="lg:col-span-3">
                    <label for="tx-category" class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ __('Category') }}</label>
                    <x-select id="tx-category" name="category">
                        <option value="">{{ __('All categories') }}</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </x-select>
                </div>

                <div class="lg:col-span-2">
                    <label for="tx-range" class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ __('Date range') }}</label>
                    <x-select id="tx-range" name="date_range">
                        <option value="all" selected>{{ __('All time') }}</option>
                        <option value="7d">{{ __('Last 7 days') }}</option>
                        <option value="30d">{{ __('Last 30 days') }}</option>
                        <option value="month">{{ __('This month') }}</option>
                    </x-select>
                </div>

                <div class="md:col-span-2 lg:col-span-2 flex flex-col justify-end">
                    <span class="mb-1.5 hidden text-xs font-medium uppercase tracking-wide text-transparent lg:block" aria-hidden="true">{{ __('Actions') }}</span>
                    <x-button type="button" variant="secondary" size="md" class="w-full lg:min-w-[9.5rem]">
                        {{ __('Apply filters') }}
                    </x-button>
                </div>
            </div>
        </form>

        <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm shadow-slate-900/5 dark:border-white/[0.08] dark:bg-slate-900 dark:shadow-[0_1px_0_0_rgba(255,255,255,0.04)]">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[640px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-200/80 bg-slate-50/90 dark:border-white/[0.08] dark:bg-slate-800/60">
                            <th scope="col" class="whitespace-nowrap px-4 py-3.5 font-semibold text-slate-600 sm:px-6 dark:text-slate-300">{{ __('Date') }}</th>
                            <th scope="col" class="whitespace-nowrap px-4 py-3.5 font-semibold text-slate-600 sm:px-6 dark:text-slate-300">{{ __('Title') }}</th>
                            <th scope="col" class="whitespace-nowrap px-4 py-3.5 font-semibold text-slate-600 sm:px-6 dark:text-slate-300">{{ __('Category') }}</th>
                            <th scope="col" class="whitespace-nowrap px-4 py-3.5 text-right font-semibold text-slate-600 sm:px-6 dark:text-slate-300">{{ __('Amount') }}</th>
                            <th scope="col" class="whitespace-nowrap px-4 py-3.5 font-semibold text-slate-600 sm:px-6 dark:text-slate-300">{{ __('Type') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/10">
                        @foreach ($transactions as $row)
                            <tr class="transition-colors hover:bg-slate-50/80 dark:hover:bg-white/[0.04]">
                                <td class="whitespace-nowrap px-4 py-4 tabular-nums text-slate-600 sm:px-6 dark:text-slate-400">
                                    {{ \Carbon\Carbon::parse($row['date'])->format('M j, Y') }}
                                </td>
                                <td class="px-4 py-4 font-medium text-slate-900 sm:px-6 dark:text-white">
                                    {{ $row['title'] }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-4 text-slate-600 sm:px-6 dark:text-slate-400">
                                    {{ $row['category'] }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-4 text-right font-semibold tabular-nums sm:px-6 {{ $row['type'] === 'income' ? 'text-accent dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                                    {{ $row['type'] === 'income' ? '+' : '-' }}${{ number_format($row['amount'], 2) }}
                                </td>
                                <td class="px-4 py-4 sm:px-6">
                                    @if ($row['type'] === 'income')
                                        <span class="inline-flex rounded-full bg-accent-muted px-2.5 py-0.5 text-xs font-medium text-accent-dark dark:bg-emerald-950/50 dark:text-emerald-300">{{ __('Income') }}</span>
                                    @else
                                        <span class="inline-flex rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-medium text-red-700 dark:bg-red-950/40 dark:text-red-300">{{ __('Expense') }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <nav
                class="border-t border-slate-200/80 bg-slate-50/90 px-4 py-5 shadow-[inset_0_1px_0_0_rgba(255,255,255,0.6)] dark:border-white/[0.08] dark:bg-slate-900 dark:shadow-[inset_0_1px_0_0_rgba(255,255,255,0.04)] sm:px-6"
                role="navigation"
                aria-label="{{ __('Pagination Navigation') }}"
            >
                <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between lg:gap-8">
                    <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center sm:gap-x-6 sm:gap-y-3">
                        <p class="text-sm leading-snug text-slate-600 dark:text-slate-400">
                            <span class="text-slate-500 dark:text-slate-500">{{ __('Showing') }}</span>
                            <span class="mx-1 font-semibold tabular-nums text-slate-900 dark:text-white">1</span>
                            <span class="text-slate-400 dark:text-slate-500">{{ __('to') }}</span>
                            <span class="mx-1 font-semibold tabular-nums text-slate-900 dark:text-white">{{ $demoShowingTo }}</span>
                            <span class="text-slate-500 dark:text-slate-500">{{ __('of') }}</span>
                            <span class="mx-1 font-semibold tabular-nums text-slate-900 dark:text-white">{{ $txTotal }}</span>
                            <span class="text-slate-500 dark:text-slate-500">{{ __('results') }}</span>
                        </p>

                        <div class="flex items-center gap-2.5 rounded-xl bg-white/90 px-3 py-2 ring-1 ring-slate-200/70 dark:bg-slate-950/60 dark:ring-white/10">
                            <label for="tx-per-page" class="whitespace-nowrap text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                {{ __('Per page') }}
                            </label>
                            <div class="w-[4.75rem] shrink-0 sm:w-[5.25rem]">
                                <x-select id="tx-per-page" name="per_page" class="!border-slate-200/90 !bg-white !py-2 !text-sm dark:!border-white/12 dark:!bg-slate-900">
                                    <option value="10" selected>10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </x-select>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between sm:gap-4 lg:justify-end">
                        <div class="flex items-center justify-between gap-3 sm:justify-end lg:gap-3">
                            <button
                                type="button"
                                disabled
                                class="inline-flex min-h-10 min-w-10 cursor-not-allowed items-center justify-center rounded-xl border border-slate-200/90 bg-white text-slate-300 shadow-sm dark:border-white/[0.08] dark:bg-slate-800/40 dark:text-slate-600"
                                aria-label="{{ __('pagination.previous') }}"
                            >
                                <svg class="size-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </button>

                            <div
                                class="flex items-center gap-0.5 rounded-2xl bg-white/90 p-1 ring-1 ring-slate-200/80 dark:bg-slate-950/50 dark:ring-white/10"
                                role="group"
                                aria-label="{{ __('Page') }}"
                            >
                                <span
                                    class="flex min-h-9 min-w-9 items-center justify-center rounded-xl bg-brand-muted text-sm font-semibold text-brand shadow-sm shadow-brand/10 dark:bg-brand/30 dark:text-indigo-50 dark:shadow-none"
                                    aria-current="page"
                                >
                                    1
                                </span>
                                <a
                                    href="#"
                                    class="flex min-h-9 min-w-9 items-center justify-center rounded-xl text-sm font-medium text-slate-600 transition-colors hover:bg-slate-100 hover:text-slate-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2 dark:text-slate-400 dark:hover:bg-white/10 dark:hover:text-white dark:focus-visible:ring-offset-slate-900"
                                    aria-label="{{ __('Go to page :page', ['page' => 2]) }}"
                                    onclick="event.preventDefault()"
                                >
                                    2
                                </a>
                                <a
                                    href="#"
                                    class="flex min-h-9 min-w-9 items-center justify-center rounded-xl text-sm font-medium text-slate-600 transition-colors hover:bg-slate-100 hover:text-slate-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2 dark:text-slate-400 dark:hover:bg-white/10 dark:hover:text-white dark:focus-visible:ring-offset-slate-900"
                                    aria-label="{{ __('Go to page :page', ['page' => 3]) }}"
                                    onclick="event.preventDefault()"
                                >
                                    3
                                </a>
                            </div>

                            <a
                                href="#"
                                rel="next"
                                class="inline-flex min-h-10 min-w-10 items-center justify-center rounded-xl border border-slate-200/90 bg-white text-slate-600 shadow-sm transition-colors hover:border-slate-300/90 hover:bg-slate-50 hover:text-slate-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2 dark:border-white/12 dark:bg-slate-800 dark:text-slate-300 dark:hover:border-white/18 dark:hover:bg-slate-700/90 dark:hover:text-white dark:focus-visible:ring-offset-slate-900"
                                aria-label="{{ __('pagination.next') }}"
                                onclick="event.preventDefault()"
                            >
                                <svg class="size-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </div>

                        <p class="text-center text-xs font-medium tabular-nums text-slate-500 dark:text-slate-500 sm:hidden">
                            {{ __('Page :current of :last', ['current' => 1, 'last' => $demoPageCount]) }}
                        </p>
                    </div>
                </div>
            </nav>
        </div>

        <x-transactions.create-modal />
    </div>
</x-layout.app-layout>
