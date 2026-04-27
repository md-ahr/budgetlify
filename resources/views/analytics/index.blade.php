<x-layout.app-layout>
    @php
        $monthLabels = [__('Jan'), __('Feb'), __('Mar'), __('Apr'), __('May'), __('Jun')];
        $monthlySpend = [2100, 2750, 1900, 3600, 2400, 3200];
        $monthlyTrend = [1800, 2400, 2200, 3000, 2600, 2900];

        $categories = [
            ['label' => __('Housing'), 'pct' => 32, 'swatch' => 'bg-brand'],
            ['label' => __('Groceries'), 'pct' => 18, 'swatch' => 'bg-accent'],
            ['label' => __('Transport'), 'pct' => 14, 'swatch' => 'bg-indigo-400'],
            ['label' => __('Dining'), 'pct' => 12, 'swatch' => 'bg-amber-400'],
            ['label' => __('Other'), 'pct' => 24, 'swatch' => 'bg-slate-300 dark:bg-slate-600'],
        ];
        $categoryChartLabels = array_column($categories, 'label');
        $categoryChartValues = array_column($categories, 'pct');
        $categoryChartColors = [
            'rgba(79, 70, 229, 0.92)',
            'rgba(34, 197, 94, 0.92)',
            'rgba(129, 140, 248, 0.92)',
            'rgba(251, 191, 36, 0.92)',
            'rgba(148, 163, 184, 0.88)',
        ];

        $savingsLabels = [__('Jan'), __('Feb'), __('Mar'), __('Apr'), __('May'), __('Jun')];
        $savingsCumulative = [2500, 3200, 4100, 5200, 6100, 7800];

        $flowLabels = [__('Income'), __('Expenses')];
        $flowValues = [8240, 5187.35];
    @endphp

    <div class="mx-auto max-w-full space-y-6">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-white">{{ __('Analytics') }}</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('Patterns in spending, savings, and cash flow — demo visuals.') }}</p>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 md:items-stretch">
            <section
                class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm shadow-slate-900/5 dark:border-white/[0.08] dark:bg-slate-900 dark:shadow-[0_1px_0_0_rgba(255,255,255,0.04)] md:col-span-2"
                aria-labelledby="analytics-monthly-heading"
            >
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <h2 id="analytics-monthly-heading" class="text-base font-semibold tracking-tight text-slate-900 dark:text-white">
                            {{ __('Monthly spending') }}
                        </h2>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('Bars vs prior-period trend') }}</p>
                    </div>
                    <div class="flex flex-wrap gap-4 text-xs font-medium text-slate-500 dark:text-slate-400">
                        <span class="inline-flex items-center gap-2">
                            <span class="size-2.5 rounded-sm bg-brand/80" aria-hidden="true"></span>
                            {{ __('Spend') }}
                        </span>
                        <span class="inline-flex items-center gap-2">
                            <span class="size-2.5 rounded-full bg-slate-400 dark:bg-slate-500" aria-hidden="true"></span>
                            {{ __('Trend line') }}
                        </span>
                    </div>
                </div>

                <div class="mt-8 h-72 w-full min-h-[18rem]" role="img" aria-label="{{ __('Monthly spending bar and trend chart') }}">
                    <canvas
                        data-budgetlify-chart="analytics-monthly"
                        data-labels='@json($monthLabels)'
                        data-bar-values='@json($monthlySpend)'
                        data-line-values='@json($monthlyTrend)'
                        data-bar-label="{{ __('Spend') }}"
                        data-line-label="{{ __('Trend line') }}"
                        class="max-h-72 w-full"
                    ></canvas>
                </div>
            </section>

            <section
                class="flex flex-col rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm shadow-slate-900/5 dark:border-white/[0.08] dark:bg-slate-900 dark:shadow-[0_1px_0_0_rgba(255,255,255,0.04)]"
                aria-labelledby="analytics-category-heading"
            >
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <h2 id="analytics-category-heading" class="text-base font-semibold tracking-tight text-slate-900 dark:text-white">
                            {{ __('Category breakdown') }}
                        </h2>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('Share of spend by category') }}</p>
                    </div>
                    <span class="rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600 dark:bg-white/10 dark:text-slate-300">{{ __('Demo') }}</span>
                </div>

                <div class="mt-8 flex flex-col items-center gap-8 sm:flex-row sm:items-center sm:justify-between">
                    <div class="relative flex size-52 shrink-0 items-center justify-center sm:size-56" aria-hidden="true">
                        <canvas
                            data-budgetlify-chart="analytics-category"
                            data-labels='@json($categoryChartLabels)'
                            data-values='@json($categoryChartValues)'
                            data-colors='@json($categoryChartColors)'
                            class="size-full max-h-56 max-w-56"
                        ></canvas>
                        <div class="pointer-events-none absolute inset-0 z-10 flex items-center justify-center text-center">
                            <div>
                                <p class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ __('Total') }}</p>
                                <p class="text-lg font-semibold tabular-nums text-slate-900 dark:text-white">100%</p>
                            </div>
                        </div>
                    </div>
                    <ul class="w-full max-w-sm flex-1 space-y-3" role="list">
                        @foreach ($categories as $cat)
                            <li class="flex items-center justify-between gap-3 text-sm">
                                <span class="flex min-w-0 items-center gap-2 font-medium text-slate-700 dark:text-slate-200">
                                    <span class="size-2.5 shrink-0 rounded-full {{ $cat['swatch'] }}" aria-hidden="true"></span>
                                    <span class="truncate">{{ $cat['label'] }}</span>
                                </span>
                                <span class="shrink-0 tabular-nums text-slate-500 dark:text-slate-400">{{ $cat['pct'] }}%</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </section>

            <section
                class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm shadow-slate-900/5 dark:border-white/[0.08] dark:bg-slate-900 dark:shadow-[0_1px_0_0_rgba(255,255,255,0.04)]"
                aria-labelledby="analytics-flow-heading"
            >
                <h2 id="analytics-flow-heading" class="text-base font-semibold tracking-tight text-slate-900 dark:text-white">
                    {{ __('Income vs expense') }}
                </h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('This month — illustrative totals') }}</p>

                <div class="mt-8 grid gap-6 sm:grid-cols-2">
                    <div class="rounded-xl border border-slate-100 bg-slate-50/80 p-4 dark:border-white/[0.08] dark:bg-slate-800/50">
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ __('Income') }}</p>
                        <p class="mt-2 text-2xl font-semibold tabular-nums text-accent dark:text-emerald-400">$8,240.00</p>
                        <div class="mt-4 h-2 overflow-hidden rounded-full bg-white dark:bg-slate-800">
                            <div class="h-full w-[100%] rounded-full bg-accent/80 dark:bg-accent/70" role="presentation"></div>
                        </div>
                    </div>
                    <div class="rounded-xl border border-slate-100 bg-slate-50/80 p-4 dark:border-white/[0.08] dark:bg-slate-800/50">
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ __('Expenses') }}</p>
                        <p class="mt-2 text-2xl font-semibold tabular-nums text-red-600 dark:text-red-400">$5,187.35</p>
                        <div class="mt-4 h-2 overflow-hidden rounded-full bg-white dark:bg-slate-800">
                            <div class="h-full w-[63%] rounded-full bg-red-500/70 dark:bg-red-500/60" role="presentation"></div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 h-40 w-full min-h-[10rem]" role="img" aria-label="{{ __('Income and expense comparison chart') }}">
                    <canvas
                        data-budgetlify-chart="analytics-flow"
                        data-labels='@json($flowLabels)'
                        data-values='@json($flowValues)'
                        data-income-label="{{ __('Income') }}"
                        data-expense-label="{{ __('Expenses') }}"
                        class="max-h-40 w-full"
                    ></canvas>
                </div>

                <p class="mt-6 text-center text-sm font-medium text-slate-600 dark:text-slate-300">
                    {{ __('Net') }}: <span class="text-accent dark:text-emerald-400">+$3,052.65</span>
                </p>
            </section>

            <section
                class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm shadow-slate-900/5 dark:border-white/[0.08] dark:bg-slate-900 dark:shadow-[0_1px_0_0_rgba(255,255,255,0.04)] md:col-span-2"
                aria-labelledby="analytics-savings-heading"
            >
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <h2 id="analytics-savings-heading" class="text-base font-semibold tracking-tight text-slate-900 dark:text-white">
                            {{ __('Savings trend') }}
                        </h2>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('Cumulative savings over time') }}</p>
                    </div>
                    <span class="rounded-lg bg-accent-muted px-2.5 py-1 text-xs font-medium text-accent-dark dark:bg-emerald-950/40 dark:text-emerald-300">{{ __('On track') }}</span>
                </div>

                <div class="mt-8 h-64 w-full min-h-[16rem]" role="img" aria-label="{{ __('Savings trend line chart') }}">
                    <canvas
                        data-budgetlify-chart="analytics-savings"
                        data-labels='@json($savingsLabels)'
                        data-values='@json($savingsCumulative)'
                        data-dataset-label="{{ __('Cumulative saved') }}"
                        class="max-h-64 w-full"
                    ></canvas>
                </div>
            </section>
        </div>
    </div>
</x-layout.app-layout>
