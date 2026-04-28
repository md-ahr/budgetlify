<x-layout.app-layout>
    <div class="mx-auto max-w-full space-y-8">
        <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
            <x-dashboard.stat-card label="{{ __('Total balance') }}" hint="{{ __('Income minus expenses (all time)') }}">
                {{ $money($totalBalance) }}
            </x-dashboard.stat-card>
            <x-dashboard.stat-card label="{{ __('Monthly income') }}" hint="{{ __('This calendar month') }}">
                <span class="text-accent">+{{ $money($monthlyIncome) }}</span>
            </x-dashboard.stat-card>
            <x-dashboard.stat-card label="{{ __('Monthly expenses') }}" hint="{{ __('This calendar month') }}">
                {{ $money($monthlyExpense) }}
            </x-dashboard.stat-card>
            <x-dashboard.stat-card label="{{ __('Savings rate') }}" hint="{{ __('Share of income left after expenses this month') }}">
                @if ($savingsRate !== null)
                    <span class="text-accent">{{ $savingsRate }}%</span>
                @else
                    <span class="text-slate-400 dark:text-slate-500">—</span>
                @endif
            </x-dashboard.stat-card>
        </div>

        <div class="grid items-stretch gap-6 lg:grid-cols-3">
            <section
                class="flex min-h-[24rem] flex-col rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm shadow-slate-900/5 dark:border-white/[0.08] dark:bg-slate-900 dark:shadow-[0_1px_0_0_rgba(255,255,255,0.04)] lg:col-span-2 lg:min-h-[32rem]"
                aria-labelledby="expense-chart-heading"
            >
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div class="min-w-0 flex-1">
                        <h2 id="expense-chart-heading" class="text-base font-semibold tracking-tight text-slate-900 dark:text-white">
                            {{ __('Expense overview') }}
                        </h2>
                        <p id="dashboard-expense-hint" class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                            {{ $expenseRanges['7d']['hint'] }}
                        </p>
                    </div>
                    <div class="flex w-full flex-wrap items-center gap-2 sm:w-auto sm:justify-end">
                        <div class="w-full min-w-[10.5rem] sm:w-48">
                            <label for="dashboard-expense-range" class="sr-only">{{ __('Time range') }}</label>
                            <x-select id="dashboard-expense-range" name="dashboard_expense_range">
                                <option value="7d" selected>{{ __('Last 7 days') }}</option>
                                <option value="30d">{{ __('Last 28 days') }}</option>
                                <option value="365d">{{ __('Last 12 months') }}</option>
                            </x-select>
                        </div>
                    </div>
                </div>

                <div
                    class="mt-6 flex min-h-0 min-w-0 flex-1 flex-col"
                    aria-label="{{ __('Bar chart showing expenses for the selected period') }}"
                >
                    <div class="relative min-h-[14rem] flex-1">
                        <canvas
                            data-budgetlify-chart="dashboard-expenses"
                            data-expense-ranges='@json($expenseRanges)'
                            data-dataset-label="{{ __('Spend') }}"
                            class="block h-full min-h-[14rem] w-full"
                        ></canvas>
                    </div>
                </div>
            </section>

            <section
                class="flex min-h-[24rem] flex-col rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm shadow-slate-900/5 dark:border-white/[0.08] dark:bg-slate-900 dark:shadow-[0_1px_0_0_rgba(255,255,255,0.04)] lg:min-h-0"
                aria-labelledby="recent-tx-heading"
            >
                <div class="flex items-center justify-between gap-2">
                    <h2 id="recent-tx-heading" class="text-base font-semibold tracking-tight text-slate-900 dark:text-white">
                        {{ __('Recent transactions') }}
                    </h2>
                    <a
                        href="{{ route('transactions') }}"
                        class="text-sm font-medium text-brand hover:text-brand-dark focus:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2 dark:focus-visible:ring-offset-slate-950"
                    >{{ __('View all') }}</a>
                </div>

                @if ($recentTransactions === [])
                    <p class="mt-8 flex-1 text-center text-sm text-slate-500 dark:text-slate-400">
                        {{ __('No transactions yet. Add one from the transactions page.') }}
                    </p>
                @else
                    <ul class="mt-6 flex-1 divide-y divide-slate-100 dark:divide-white/10" role="list">
                        @foreach ($recentTransactions as $tx)
                            <li class="flex gap-3 py-4 first:pt-0">
                                <div class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-600 dark:bg-white/10 dark:text-slate-300">
                                    <span class="text-xs font-semibold" aria-hidden="true">{{ mb_substr($tx['title'], 0, 1) }}</span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate font-medium text-slate-900 dark:text-white">{{ $tx['title'] }}</p>
                                    <p class="truncate text-sm text-slate-500 dark:text-slate-400">
                                        {{ $tx['category'] !== '' ? $tx['category'].' · ' : '' }}{{ $tx['date'] }}
                                    </p>
                                </div>
                                <p class="shrink-0 text-sm font-semibold tabular-nums {{ $tx['income'] ? 'text-accent' : 'text-slate-800 dark:text-slate-200' }}">
                                    {{ $tx['income'] ? '+' : '-' }}{{ $money($tx['amount']) }}
                                </p>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>
        </div>
    </div>
</x-layout.app-layout>
