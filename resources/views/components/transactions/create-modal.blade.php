@php
    $categories = ['Bills', 'Dining', 'Groceries', 'Income', 'Salary', 'Shopping', 'Transport'];
    $transactionFormErrorKeys = ['title', 'amount', 'type', 'category', 'occurred_on', 'notes'];
    $transactionFormHasErrors = $errors->hasAny($transactionFormErrorKeys);
    $isEditingTransaction = (bool) old('editing_transaction_id');
@endphp

<x-modal
    id="create-transaction-dialog"
    labelledby="create-transaction-title"
    :persist-with-errors="$transactionFormHasErrors"
    data-store-url="{{ route('transactions.store') }}"
    data-transactions-base="{{ url('/transactions') }}"
    data-default-date="{{ now()->format('Y-m-d') }}"
    data-text-add-title="{{ __('Add Transaction') }}"
    data-text-edit-title="{{ __('Edit transaction') }}"
    data-text-add-subtitle="{{ __('Record income or spending. Data stays local in this demo until you connect a backend') }}"
    data-text-edit-subtitle="{{ __('Update the details for this entry.') }}"
    data-text-save="{{ __('Save Transaction') }}"
    data-text-save-changes="{{ __('Save changes') }}"
>
    <x-slot name="header">
        <h2 id="create-transaction-title" class="text-lg font-semibold tracking-tight text-slate-900 dark:text-white">
            <span id="transaction-modal-heading">{{ $isEditingTransaction ? __('Edit transaction') : __('Add Transaction') }}</span>
        </h2>
        <p id="transaction-modal-subtitle" class="mt-1.5 text-sm leading-relaxed text-slate-500 dark:text-slate-400">
            {{ $isEditingTransaction ? __('Update the details for this entry.') : __('Record income or spending. Data stays local in this demo until you connect a backend') }}
        </p>
    </x-slot>

    <form
        id="create-transaction-form"
        action="{{ $isEditingTransaction ? route('transactions.update', ['transaction' => old('editing_transaction_id')]) : route('transactions.store') }}"
        method="post"
        class="space-y-5 px-6 py-6"
    >
        @csrf
        <input type="hidden" name="_method" id="transaction-form-method" value="PATCH" @disabled(! $isEditingTransaction) />
        <input type="hidden" name="editing_transaction_id" id="transaction-editing-id" value="{{ old('editing_transaction_id') }}" @disabled(! $isEditingTransaction) />

        <div>
            <label for="transaction-title" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Title</label>
            <input
                id="transaction-title"
                name="title"
                type="text"
                autocomplete="off"
                value="{{ old('title') }}"
                placeholder="e.g. Grocery run"
                class="w-full rounded-xl border border-slate-200/80 bg-white px-3 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20 dark:border-white/[0.1] dark:bg-slate-950 dark:text-slate-100 dark:placeholder:text-slate-500 dark:focus:ring-brand/30"
            />
            <x-error name="title" />
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label for="transaction-amount" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Amount</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400" aria-hidden="true">$</span>
                    <input
                        id="transaction-amount"
                        name="amount"
                        type="number"
                        inputmode="decimal"
                        min="0.01"
                        step="0.01"
                        value="{{ old('amount') }}"
                        placeholder="0.00"
                        class="w-full rounded-xl border border-slate-200/80 bg-white py-2.5 pl-7 pr-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20 dark:border-white/[0.1] dark:bg-slate-950 dark:text-slate-100 dark:placeholder:text-slate-500 dark:focus:ring-brand/30"
                    />
                </div>
                <x-error name="amount" />
            </div>
            <div>
                <label for="transaction-type" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Type</label>
                <x-select id="transaction-type" name="type">
                    <option value="" disabled @selected(old('type') === null || old('type') === '')>Select type</option>
                    <option value="income" @selected(old('type') === 'income')>Income</option>
                    <option value="expense" @selected(old('type') === 'expense')>Expense</option>
                </x-select>
                <x-error name="type" />
            </div>
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label for="transaction-category" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Category</label>
                <x-select id="transaction-category" name="category">
                    <option value="" disabled @selected(old('category') === null || old('category') === '')>Select category</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}" @selected(old('category') === $cat)>{{ $cat }}</option>
                    @endforeach
                </x-select>
                <x-error name="category" />
            </div>
            <div>
                <label for="transaction-date" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Date</label>
                <input
                    id="transaction-date"
                    name="occurred_on"
                    type="date"
                    value="{{ old('occurred_on', now()->format('Y-m-d')) }}"
                    class="w-full rounded-xl border border-slate-200/80 bg-white px-3 py-2.5 text-sm text-slate-900 focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20 dark:border-white/[0.1] dark:bg-slate-950 dark:text-slate-100 dark:focus:ring-brand/30"
                />
                <x-error name="occurred_on" />
            </div>
        </div>

        <div>
            <label for="transaction-notes" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Notes</label>
            <textarea
                id="transaction-notes"
                name="notes"
                rows="4"
                placeholder="Optional details, receipt hints, or tags…"
                class="w-full resize-y rounded-xl border border-slate-200/80 bg-white px-3 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20 dark:border-white/[0.1] dark:bg-slate-950 dark:text-slate-100 dark:placeholder:text-slate-500 dark:focus:ring-brand/30"
            >{{ old('notes') }}</textarea>
            <x-error name="notes" />
        </div>

        <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-6 dark:border-white/10 sm:flex-row sm:justify-end">
            <x-button type="button" variant="secondary" size="md" class="w-full sm:w-auto" data-close-create-transaction="true">
                Cancel
            </x-button>
            <x-button type="submit" variant="primary" size="md" class="w-full sm:w-auto">
                <span id="transaction-submit-label">{{ $isEditingTransaction ? __('Save changes') : __('Save Transaction') }}</span>
            </x-button>
        </div>
    </form>
</x-modal>
