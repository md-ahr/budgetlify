@php
    $paginationCurrent = $transactions->currentPage();
    $paginationLast = max(1, $transactions->lastPage());
    $windowStart = max(1, $paginationCurrent - 2);
    $windowEnd = min($paginationLast, $paginationCurrent + 2);
@endphp

<x-layout.app-layout>
    <div class="mx-auto max-w-full space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-white">Transactions</h1>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Review and filter your activity across accounts.</p>
            </div>
            <x-button type="button" variant="primary" size="md" class="w-full shrink-0 sm:w-auto" data-open-transaction-modal="true">
                Add Transaction
            </x-button>
        </div>

        <form
            action="{{ route('transactions') }}"
            method="get"
            class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm shadow-slate-900/5 dark:border-white/8 dark:bg-slate-900 dark:shadow-[0_1px_0_0_rgba(255,255,255,0.04)] sm:p-6"
        >
            <input type="hidden" name="per_page" value="{{ $perPage }}" />

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
                            value="{{ $filters['search'] }}"
                            placeholder="{{ __('Search by title…') }}"
                            class="w-full rounded-xl border border-slate-200/80 bg-slate-50 py-2.5 pl-9 pr-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 transition-colors hover:border-slate-300/90 focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20 dark:border-white/[0.1] dark:bg-slate-950 dark:text-slate-100 dark:placeholder:text-slate-500 dark:hover:border-white/[0.14] dark:focus:ring-brand/30"
                        />
                    </div>
                </div>

                <div class="lg:col-span-3">
                    <label for="tx-category" class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ __('Category') }}</label>
                    <x-select id="tx-category" name="category">
                        <option value="" @selected($filters['category'] === '')>{{ __('All categories') }}</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat }}" @selected($filters['category'] === $cat)>{{ $cat }}</option>
                        @endforeach
                    </x-select>
                </div>

                <div class="lg:col-span-2">
                    <label for="tx-range" class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ __('Date range') }}</label>
                    <x-select id="tx-range" name="date_range">
                        <option value="all" @selected($filters['date_range'] === 'all')>{{ __('All time') }}</option>
                        <option value="7d" @selected($filters['date_range'] === '7d')>{{ __('Last 7 days') }}</option>
                        <option value="30d" @selected($filters['date_range'] === '30d')>{{ __('Last 30 days') }}</option>
                        <option value="month" @selected($filters['date_range'] === 'month')>{{ __('Last 1 year') }}</option>
                    </x-select>
                </div>

                <div class="md:col-span-2 lg:col-span-2 flex flex-col justify-end gap-3">
                    <span class="mb-1.5 hidden text-xs font-medium uppercase tracking-wide text-transparent lg:block" aria-hidden="true">Actions</span>
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-stretch">
                        <x-button type="submit" variant="secondary" size="md" class="w-full lg:min-w-38">
                            {{ __('Apply Filters') }}
                        </x-button>
                        @if ($hasActiveFilters)
                            <x-button
                                href="{{ route('transactions', $perPage === 10 ? [] : ['per_page' => $perPage]) }}"
                                variant="ghost"
                                size="md"
                                class="w-full shrink-0 sm:w-auto"
                                aria-label="{{ __('Clear filters') }}"
                            >
                                <svg class="size-4 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                                {{ __('Clear') }}
                            </x-button>
                        @endif
                    </div>
                </div>
            </div>
        </form>

        <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm shadow-slate-900/5 dark:border-white/8 dark:bg-slate-900 dark:shadow-[0_1px_0_0_rgba(255,255,255,0.04)]">
            <div class="overflow-x-auto">
                <table class="w-full min-w-160 text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-200/80 bg-slate-50/90 dark:border-white/8 dark:bg-slate-800/60">
                            <th scope="col" class="whitespace-nowrap px-4 py-3.5 font-semibold text-slate-600 sm:px-6 dark:text-slate-300">Date</th>
                            <th scope="col" class="whitespace-nowrap px-4 py-3.5 font-semibold text-slate-600 sm:px-6 dark:text-slate-300">Title</th>
                            <th scope="col" class="whitespace-nowrap px-4 py-3.5 font-semibold text-slate-600 sm:px-6 dark:text-slate-300">Category</th>
                            <th scope="col" class="whitespace-nowrap px-4 py-3.5 text-right font-semibold text-slate-600 sm:px-6 dark:text-slate-300">Amount</th>
                            <th scope="col" class="whitespace-nowrap px-4 py-3.5 font-semibold text-slate-600 sm:px-6 dark:text-slate-300">Type</th>
                            <th scope="col" class="whitespace-nowrap px-4 py-3.5 font-semibold text-slate-600 sm:px-6 dark:text-slate-300">Notes</th>
                            <th scope="col" class="whitespace-nowrap px-4 py-3.5 font-semibold text-slate-600 sm:px-6 dark:text-slate-300">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/10">
                        @forelse ($transactions as $row)
                            <tr class="transition-colors hover:bg-slate-50/80 dark:hover:bg-white/4">
                                <td class="whitespace-nowrap px-4 py-4 tabular-nums text-slate-600 sm:px-6 dark:text-slate-400">
                                    {{ $formatUserDate($row->occurred_on) }}
                                </td>
                                <td class="px-4 py-4 font-medium text-slate-900 sm:px-6 dark:text-white">
                                    {{ $row->title }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-4 text-slate-600 sm:px-6 dark:text-slate-400">
                                    {{ $row->category }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-4 text-right font-semibold tabular-nums sm:px-6 {{ $row->type === 'income' ? 'text-accent dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                                    {{ $row->type === 'income' ? '+' : '-' }}{{ $money((float) $row->amount) }}
                                </td>
                                <td class="px-4 py-4 sm:px-6">
                                    @if ($row->type === 'income')
                                        <span class="inline-flex rounded-full bg-accent-muted px-2.5 py-0.5 text-xs font-medium text-accent-dark dark:bg-emerald-950/50 dark:text-emerald-300">{{ __('Income') }}</span>
                                    @else
                                        <span class="inline-flex rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-medium text-red-700 dark:bg-red-950/40 dark:text-red-300">{{ __('Expense') }}</span>
                                    @endif
                                </td>
                                <td class="max-w-xs truncate px-4 py-4 text-slate-600 sm:px-6 dark:text-slate-400" title="{{ $row->notes }}">
                                    {{ $row->notes ? $row->notes : '—' }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-4 sm:px-6">
                                    <div class="flex items-center gap-1">
                                        <button
                                            type="button"
                                            data-edit-transaction="{{ json_encode(array_merge($row->only(['id', 'title', 'type', 'category', 'occurred_on', 'notes']), ['amount' => \App\Support\Money::toDisplayAmount((float) $row->amount)]), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) }}"
                                            class="inline-flex size-9 items-center justify-center rounded-xl text-slate-500 transition-colors hover:bg-slate-100 hover:text-slate-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2 dark:text-slate-400 dark:hover:bg-white/10 dark:hover:text-white dark:focus-visible:ring-offset-slate-900"
                                            aria-label="{{ __('Edit transaction') }}"
                                        >
                                            <svg class="size-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                            </svg>
                                        </button>
                                        <form
                                            method="post"
                                            action="{{ route('transactions.destroy', $row) }}"
                                            class="inline"
                                            onsubmit="return confirm({{ json_encode(__('Are you sure you want to delete this transaction?')) }})"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="inline-flex size-9 cursor-pointer items-center justify-center rounded-xl text-slate-500 transition-colors hover:bg-red-50 hover:text-red-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2 dark:text-slate-400 dark:hover:bg-red-950/40 dark:hover:text-red-400 dark:focus-visible:ring-offset-slate-900"
                                                aria-label="Delete transaction"
                                            >
                                                <svg class="size-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-12 text-center text-sm text-slate-500 dark:text-slate-400 sm:px-6">
                                    @if ($hasActiveFilters)
                                        {{ __('No transactions match your filters.') }}
                                    @else
                                        {{ __('No transactions yet. Add one to get started.') }}
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <nav
                class="border-t border-slate-200/80 bg-slate-50/90 px-4 py-5 shadow-[inset_0_1px_0_0_rgba(255,255,255,0.6)] dark:border-white/8 dark:bg-slate-900 dark:shadow-[inset_0_1px_0_0_rgba(255,255,255,0.04)] sm:px-6"
                role="navigation"
                aria-label="{{ __('Pagination Navigation') }}"
            >
                <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between lg:gap-8">
                    <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center sm:gap-x-6 sm:gap-y-3">
                        <p class="text-sm leading-snug text-slate-600 dark:text-slate-400">
                            @if ($transactions->total() === 0)
                                <span class="text-slate-500 dark:text-slate-500">{{ __('Showing') }}</span>
                                <span class="mx-1 font-semibold tabular-nums text-slate-900 dark:text-white">0</span>
                                <span class="text-slate-500 dark:text-slate-500">{{ __('results') }}</span>
                            @else
                                <span class="text-slate-500 dark:text-slate-500">{{ __('Showing') }}</span>
                                <span class="mx-1 font-semibold tabular-nums text-slate-900 dark:text-white">{{ $transactions->firstItem() }}</span>
                                <span class="text-slate-400 dark:text-slate-500">{{ __('to') }}</span>
                                <span class="mx-1 font-semibold tabular-nums text-slate-900 dark:text-white">{{ $transactions->lastItem() }}</span>
                                <span class="text-slate-500 dark:text-slate-500">{{ __('of') }}</span>
                                <span class="mx-1 font-semibold tabular-nums text-slate-900 dark:text-white">{{ $transactions->total() }}</span>
                                <span class="text-slate-500 dark:text-slate-500">{{ __('results') }}</span>
                            @endif
                        </p>

                        <form method="get" action="{{ route('transactions') }}" class="flex items-center gap-2.5 rounded-xl bg-white/90 px-3 py-2 ring-1 ring-slate-200/70 dark:bg-slate-950/60 dark:ring-white/10">
                            @if ($filters['search'] !== '')
                                <input type="hidden" name="search" value="{{ $filters['search'] }}" />
                            @endif
                            @if ($filters['category'] !== '')
                                <input type="hidden" name="category" value="{{ $filters['category'] }}" />
                            @endif
                            @if ($filters['date_range'] !== 'all')
                                <input type="hidden" name="date_range" value="{{ $filters['date_range'] }}" />
                            @endif
                            <label for="tx-per-page" class="whitespace-nowrap text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                {{ __('Per page') }}
                            </label>
                            <div class="w-19 shrink-0 sm:w-21">
                                <x-select id="tx-per-page" name="per_page" class="border-slate-200/90! bg-white! py-2! text-sm! dark:border-white/12! dark:bg-slate-900!" onchange="this.form.requestSubmit()">
                                    <option value="10" @selected($perPage === 10)>10</option>
                                    <option value="25" @selected($perPage === 25)>25</option>
                                    <option value="50" @selected($perPage === 50)>50</option>
                                    <option value="100" @selected($perPage === 100)>100</option>
                                </x-select>
                            </div>
                        </form>
                    </div>

                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between sm:gap-4 lg:justify-end">
                        <div class="flex items-center justify-between gap-3 sm:justify-end lg:gap-3">
                            @if ($transactions->onFirstPage())
                                <span
                                    class="inline-flex min-h-10 min-w-10 cursor-not-allowed items-center justify-center rounded-xl border border-slate-200/90 bg-white text-slate-300 shadow-sm dark:border-white/[0.08] dark:bg-slate-800/40 dark:text-slate-600"
                                    aria-label="{{ __('pagination.previous') }}"
                                    aria-disabled="true"
                                >
                                    <svg class="size-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                            @else
                                <a
                                    href="{{ $transactions->previousPageUrl() }}"
                                    rel="prev"
                                    class="inline-flex min-h-10 min-w-10 items-center justify-center rounded-xl border border-slate-200/90 bg-white text-slate-600 shadow-sm transition-colors hover:border-slate-300/90 hover:bg-slate-50 hover:text-slate-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2 dark:border-white/12 dark:bg-slate-800 dark:text-slate-300 dark:hover:border-white/18 dark:hover:bg-slate-700/90 dark:hover:text-white dark:focus-visible:ring-offset-slate-900"
                                    aria-label="{{ __('pagination.previous') }}"
                                >
                                    <svg class="size-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            @endif

                            <div
                                class="flex items-center gap-0.5 rounded-2xl bg-white/90 p-1 ring-1 ring-slate-200/80 dark:bg-slate-950/50 dark:ring-white/10"
                                role="group"
                                aria-label="{{ __('Page') }}"
                            >
                                @for ($page = $windowStart; $page <= $windowEnd; $page++)
                                    @if ($page === $paginationCurrent)
                                        <span
                                            class="flex min-h-9 min-w-9 items-center justify-center rounded-xl bg-brand-muted text-sm font-semibold text-brand shadow-sm shadow-brand/10 dark:bg-brand/30 dark:text-indigo-50 dark:shadow-none"
                                            aria-current="page"
                                        >
                                            {{ $page }}
                                        </span>
                                    @else
                                        <a
                                            href="{{ $transactions->url($page) }}"
                                            class="flex min-h-9 min-w-9 items-center justify-center rounded-xl text-sm font-medium text-slate-600 transition-colors hover:bg-slate-100 hover:text-slate-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2 dark:text-slate-400 dark:hover:bg-white/10 dark:hover:text-white dark:focus-visible:ring-offset-slate-900"
                                            aria-label="{{ __('Go to page :page', ['page' => $page]) }}"
                                        >
                                            {{ $page }}
                                        </a>
                                    @endif
                                @endfor
                            </div>

                            @if ($transactions->hasMorePages())
                                <a
                                    href="{{ $transactions->nextPageUrl() }}"
                                    rel="next"
                                    class="inline-flex min-h-10 min-w-10 items-center justify-center rounded-xl border border-slate-200/90 bg-white text-slate-600 shadow-sm transition-colors hover:border-slate-300/90 hover:bg-slate-50 hover:text-slate-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2 dark:border-white/12 dark:bg-slate-800 dark:text-slate-300 dark:hover:border-white/18 dark:hover:bg-slate-700/90 dark:hover:text-white dark:focus-visible:ring-offset-slate-900"
                                    aria-label="{{ __('pagination.next') }}"
                                >
                                    <svg class="size-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            @else
                                <span
                                    class="inline-flex min-h-10 min-w-10 cursor-not-allowed items-center justify-center rounded-xl border border-slate-200/90 bg-white text-slate-300 shadow-sm dark:border-white/[0.08] dark:bg-slate-800/40 dark:text-slate-600"
                                    aria-label="{{ __('pagination.next') }}"
                                    aria-disabled="true"
                                >
                                    <svg class="size-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                            @endif
                        </div>

                        <p class="text-center text-xs font-medium tabular-nums text-slate-500 dark:text-slate-500 sm:hidden">
                            {{ __('Page :current of :last', ['current' => $paginationCurrent, 'last' => $paginationLast]) }}
                        </p>
                    </div>
                </div>
            </nav>
        </div>

        <x-transactions.create-modal />
    </div>
</x-layout.app-layout>
