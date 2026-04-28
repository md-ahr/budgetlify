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

        @if ($budgets->isEmpty())
            <div class="rounded-2xl border border-dashed border-slate-200/80 bg-white p-10 text-center dark:border-white/10 dark:bg-slate-900">
                <p class="text-sm text-slate-600 dark:text-slate-400">{{ __('No budgets yet. Create one to track spending against a monthly limit.') }}</p>
            </div>
        @else
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3">
                @foreach ($budgets as $budget)
                    <x-budgets.card
                        :budget="$budget"
                        :spent="$spentForBudget[$budget->id] ?? 0.0"
                        :spent-period-label="$spentPeriodLabel"
                    />
                @endforeach
            </div>
        @endif
    </div>

    <x-budgets.create-modal :categories="$categories" />
</x-layout.app-layout>
