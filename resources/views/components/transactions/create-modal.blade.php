@php
    $categories = ['Bills', 'Dining', 'Groceries', 'Income', 'Salary', 'Shopping', 'Transport'];
@endphp

<x-modal id="create-transaction-dialog" labelledby="create-transaction-title">
    <x-slot name="header">
        <h2 id="create-transaction-title" class="text-lg font-semibold tracking-tight text-slate-900 dark:text-white">
            {{ __('Add transaction') }}
        </h2>
        <p class="mt-1.5 text-sm leading-relaxed text-slate-500 dark:text-slate-400">
            {{ __('Record income or spending. Data stays local in this demo until you connect a backend.') }}
        </p>
    </x-slot>

    <form action="#" class="space-y-5 px-6 py-6" onsubmit="return false">
        <div>
            <label for="transaction-title" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Title') }}</label>
            <input
                id="transaction-title"
                name="title"
                type="text"
                autocomplete="off"
                value=""
                placeholder="{{ __('e.g. Grocery run') }}"
                class="w-full rounded-xl border border-slate-200/80 bg-white px-3 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20 dark:border-white/[0.1] dark:bg-slate-950 dark:text-slate-100 dark:placeholder:text-slate-500 dark:focus:ring-brand/30"
            />
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label for="transaction-amount" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Amount') }}</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400" aria-hidden="true">$</span>
                    <input
                        id="transaction-amount"
                        name="amount"
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
            <div>
                <label for="transaction-type" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Type') }}</label>
                <x-select id="transaction-type" name="type">
                    <option value="" disabled selected>{{ __('Select type') }}</option>
                    <option value="income">{{ __('Income') }}</option>
                    <option value="expense">{{ __('Expense') }}</option>
                </x-select>
            </div>
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label for="transaction-category" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Category') }}</label>
                <x-select id="transaction-category" name="category">
                    <option value="" disabled selected>{{ __('Select category') }}</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </x-select>
            </div>
            <div>
                <label for="transaction-date" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Date') }}</label>
                <input
                    id="transaction-date"
                    name="occurred_on"
                    type="date"
                    value="2026-04-27"
                    class="w-full rounded-xl border border-slate-200/80 bg-white px-3 py-2.5 text-sm text-slate-900 focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20 dark:border-white/[0.1] dark:bg-slate-950 dark:text-slate-100 dark:focus:ring-brand/30"
                />
            </div>
        </div>

        <div>
            <label for="transaction-notes" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Notes') }}</label>
            <textarea
                id="transaction-notes"
                name="notes"
                rows="4"
                placeholder="{{ __('Optional details, receipt hints, or tags…') }}"
                class="w-full resize-y rounded-xl border border-slate-200/80 bg-white px-3 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20 dark:border-white/[0.1] dark:bg-slate-950 dark:text-slate-100 dark:placeholder:text-slate-500 dark:focus:ring-brand/30"
            ></textarea>
        </div>

        <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-6 dark:border-white/10 sm:flex-row sm:justify-end">
            <x-button type="button" variant="secondary" size="md" class="w-full sm:w-auto" data-close-create-transaction="true">
                {{ __('Cancel') }}
            </x-button>
            <x-button type="button" variant="primary" size="md" class="w-full sm:w-auto">
                {{ __('Save transaction') }}
            </x-button>
        </div>
    </form>
</x-modal>
