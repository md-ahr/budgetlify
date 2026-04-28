<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Support\FinanceCategories;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TransactionController extends Controller
{
    /**
     * @var list<int>
     */
    private const ALLOWED_PER_PAGE = [10, 25, 50, 100];

    /**
     * @var list<string>
     */
    private const ALLOWED_DATE_RANGES = ['all', '7d', '30d', 'month'];

    public function index(Request $request): View
    {
        $perPage = (int) $request->input('per_page', 10);
        if (! in_array($perPage, self::ALLOWED_PER_PAGE, true)) {
            $perPage = 10;
        }

        $search = trim((string) $request->input('search', ''));
        $category = (string) $request->input('category', '');
        if ($category !== '' && ! in_array($category, FinanceCategories::ALL, true)) {
            $category = '';
        }

        $dateRange = (string) $request->input('date_range', 'all');
        if (! in_array($dateRange, self::ALLOWED_DATE_RANGES, true)) {
            $dateRange = 'all';
        }

        $hasActiveFilters = $search !== ''
            || $category !== ''
            || $dateRange !== 'all';

        $transactions = Auth::user()
            ->transactions()
            ->withTitleSearch($search)
            ->forCategory($category)
            ->withOccurredOnFrom(Transaction::minimumOccurredOnForRange($dateRange))
            ->latestFirst()
            ->paginate($perPage)
            ->withQueryString();

        return view('transactions.index', [
            'transactions' => $transactions,
            'perPage' => $perPage,
            'filters' => [
                'search' => $search,
                'category' => $category,
                'date_range' => $dateRange,
            ],
            'categories' => FinanceCategories::ALL,
            'hasActiveFilters' => $hasActiveFilters,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->transactionValidationRules());

        Auth::user()->transactions()->create($data);

        return redirect()->route('transactions');
    }

    public function update(Request $request, Transaction $transaction): RedirectResponse
    {
        abort_unless($transaction->user_id === Auth::id(), 403);

        $data = $request->validate($this->transactionValidationRules());

        $transaction->update($data);

        return redirect()->route('transactions');
    }

    public function destroy(Transaction $transaction): RedirectResponse
    {
        abort_unless($transaction->user_id === Auth::id(), 403);

        $transaction->delete();

        return redirect()->route('transactions');
    }

    /**
     * @return array<string, list<string>>
     */
    private function transactionValidationRules(): array
    {
        return [
            'title' => ['required'],
            'amount' => ['required'],
            'type' => ['required'],
            'category' => ['required', Rule::in(FinanceCategories::ALL)],
            'occurred_on' => ['required'],
            'notes' => ['nullable'],
        ];
    }
}
