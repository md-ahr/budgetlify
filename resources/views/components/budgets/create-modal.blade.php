@props(['categories'])

@php
    $budgetFormErrorKeys = ['name', 'category', 'monthly_limit'];
    $budgetFormHasErrors = $errors->hasAny($budgetFormErrorKeys);
    $isEditingBudget = (bool) old('editing_budget_id');
@endphp

<x-modal
    id="create-budget-dialog"
    labelledby="create-budget-title"
    :persist-with-errors="$budgetFormHasErrors"
    data-store-url="{{ route('budgets.store') }}"
    data-budgets-base="{{ url('/budgets') }}"
    data-text-add-title="{{ __('Create budget') }}"
    data-text-edit-title="{{ __('Edit budget') }}"
    data-text-add-subtitle="{{ __('Set a monthly cap for spending in one category.') }}"
    data-text-edit-subtitle="{{ __('Update the name, category, or limit.') }}"
    data-text-save="{{ __('Save budget') }}"
    data-text-save-changes="{{ __('Save changes') }}"
>
    <x-slot name="header">
        <h2 id="create-budget-title" class="text-lg font-semibold tracking-tight text-slate-900 dark:text-white">
            <span id="budget-modal-heading">{{ $isEditingBudget ? __('Edit budget') : __('Create budget') }}</span>
        </h2>
        <p id="budget-modal-subtitle" class="mt-1.5 text-sm leading-relaxed text-slate-500 dark:text-slate-400">
            {{ $isEditingBudget ? __('Update the name, category, or limit.') : __('Set a monthly cap for spending in one category.') }}
        </p>
    </x-slot>

    <form
        id="create-budget-form"
        action="{{ $isEditingBudget ? route('budgets.update', old('editing_budget_id')) : route('budgets.store') }}"
        method="post"
        class="space-y-5 px-6 py-6"
    >
        @csrf
        <input type="hidden" name="_method" id="budget-form-method" value="PATCH" @disabled(! $isEditingBudget) />
        <input type="hidden" name="editing_budget_id" id="budget-editing-id" value="{{ old('editing_budget_id') }}" @disabled(! $isEditingBudget) />

        <div>
            <label for="budget-name" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Name') }}</label>
            <input
                id="budget-name"
                name="name"
                type="text"
                autocomplete="off"
                value="{{ old('name') }}"
                placeholder="{{ __('e.g. Groceries cap') }}"
                class="w-full rounded-xl border border-slate-200/80 bg-white px-3 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20 dark:border-white/[0.1] dark:bg-slate-950 dark:text-slate-100 dark:placeholder:text-slate-500 dark:focus:ring-brand/30"
            />
            <x-error name="name" />
        </div>

        <div>
            <label for="budget-category" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Category') }}</label>
            <x-select id="budget-category" name="category">
                <option value="" disabled @selected(old('category') === null || old('category') === '')>{{ __('Select category') }}</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat }}" @selected(old('category') === $cat)>{{ $cat }}</option>
                @endforeach
            </x-select>
            <x-error name="category" />
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
                    value="{{ old('monthly_limit') }}"
                    placeholder="0.00"
                    class="w-full rounded-xl border border-slate-200/80 bg-white py-2.5 pl-7 pr-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20 dark:border-white/[0.1] dark:bg-slate-950 dark:text-slate-100 dark:placeholder:text-slate-500 dark:focus:ring-brand/30"
                />
            </div>
            <x-error name="monthly_limit" />
        </div>

        <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-6 dark:border-white/10 sm:flex-row sm:justify-end">
            <x-button type="button" variant="secondary" size="md" class="w-full sm:w-auto" data-close-create-budget="true">
                {{ __('Cancel') }}
            </x-button>
            <x-button type="submit" variant="primary" size="md" class="w-full sm:w-auto">
                <span id="budget-submit-label">{{ $isEditingBudget ? __('Save changes') : __('Save budget') }}</span>
            </x-button>
        </div>
    </form>
</x-modal>
