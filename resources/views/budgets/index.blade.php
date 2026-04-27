@php
    $budgets = [
        ['name' => __('Food'), 'limit' => 600.0, 'spent' => 420.0],
        ['name' => __('Rent'), 'limit' => 1800.0, 'spent' => 1650.0],
        ['name' => __('Travel'), 'limit' => 500.0, 'spent' => 575.0],
        ['name' => __('Entertainment'), 'limit' => 200.0, 'spent' => 95.0],
        ['name' => __('Utilities'), 'limit' => 350.0, 'spent' => 312.0],
    ];
@endphp

<x-layout.app-layout>
    <div class="mx-auto max-w-full space-y-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-white">{{ __('Budgets') }}</h1>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('Track limits, spending, and how close you are to each cap.') }}</p>
            </div>
            <x-button type="button" id="open-create-budget" variant="primary" size="md" class="w-full shrink-0 sm:w-auto">
                {{ __('Create budget') }}
            </x-button>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ($budgets as $budget)
                <x-budgets.card
                    :name="$budget['name']"
                    :limit="$budget['limit']"
                    :spent="$budget['spent']"
                />
            @endforeach
        </div>

        <p class="text-center text-xs text-slate-400 dark:text-slate-500">
            {{ __('Green: under ~85% of limit. Amber: 85% or more. Red: spent exceeds limit.') }}
        </p>
    </div>

    <x-modal id="create-budget-dialog" labelledby="create-budget-title">
        <x-slot name="header">
            <h2 id="create-budget-title" class="text-lg font-semibold tracking-tight text-slate-900 dark:text-white">{{ __('Create budget') }}</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('Set a monthly cap. You can connect real data later.') }}</p>
        </x-slot>

        <form action="#" class="px-6 py-5" onsubmit="return false">
            <div class="space-y-4">
                <div>
                    <label for="budget-name" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Name') }}</label>
                    <input
                        id="budget-name"
                        name="name"
                        type="text"
                        value=""
                        placeholder="{{ __('e.g. Groceries') }}"
                        class="w-full rounded-xl border border-slate-200/80 bg-white px-3 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20 dark:border-white/[0.1] dark:bg-slate-950 dark:text-slate-100 dark:placeholder:text-slate-500 dark:focus:ring-brand/30"
                    />
                </div>
                <div>
                    <label for="budget-limit" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Monthly limit') }}</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400" aria-hidden="true">$</span>
                        <input
                            id="budget-limit"
                            name="monthly_limit"
                            type="number"
                            inputmode="decimal"
                            min="0.01"
                            step="0.01"
                            value=""
                            placeholder="0.00"
                            class="w-full rounded-xl border border-slate-200/80 bg-white py-2.5 pl-7 pr-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20 dark:border-white/[0.1] dark:bg-slate-950 dark:text-slate-100 dark:placeholder:text-slate-500 dark:focus:ring-brand/30"
                        />
                    </div>
                </div>
            </div>

            <div class="mt-6 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
                <x-button type="button" variant="secondary" size="md" class="w-full sm:w-auto" data-close-create-budget="true">
                    {{ __('Cancel') }}
                </x-button>
                <x-button type="button" variant="primary" size="md" class="w-full sm:w-auto">
                    {{ __('Save budget') }}
                </x-button>
            </div>
        </form>
    </x-modal>
</x-layout.app-layout>
