<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Transaction;
use App\Support\FinanceCategories;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BudgetController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $month = now();
        $start = $month->copy()->startOfMonth()->toDateString();
        $end = $month->copy()->endOfMonth()->toDateString();

        $spentByCategory = Transaction::expenseTotalsByCategoryForUser($user->id, $start, $end);

        $budgets = $user->budgets()->orderBy('name')->get();

        $spentForBudget = $budgets->mapWithKeys(fn (Budget $budget): array => [
            $budget->id => $spentByCategory->get(
                Transaction::normalizedCategoryKeyForBudgetLookup($budget->category),
                0.0,
            ),
        ]);

        return view('budgets.index', [
            'budgets' => $budgets,
            'spentForBudget' => $spentForBudget,
            'categories' => FinanceCategories::ALL,
            'spentPeriodLabel' => $month->isoFormat('MMMM YYYY'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Auth::user()->budgets()->create($this->validatedBudget($request));

        return redirect()->route('budgets');
    }

    public function update(Request $request, Budget $budget): RedirectResponse
    {
        abort_unless($budget->user_id === Auth::id(), 403);

        $budget->update($this->validatedBudget($request, $budget));

        return redirect()->route('budgets');
    }

    public function destroy(Budget $budget): RedirectResponse
    {
        abort_unless($budget->user_id === Auth::id(), 403);

        $budget->delete();

        return redirect()->route('budgets');
    }

    /**
     * @return array{name: string, category: string, monthly_limit: string}
     */
    private function validatedBudget(Request $request, ?Budget $budget = null): array
    {
        $categoryUnique = Rule::unique('budgets', 'category')->where('user_id', Auth::id());
        if ($budget !== null) {
            $categoryUnique = $categoryUnique->ignore($budget->id);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', Rule::in(FinanceCategories::ALL), $categoryUnique],
            'monthly_limit' => ['required', 'numeric', 'min:0.01'],
        ], [], [
            'monthly_limit' => __('Monthly limit'),
        ]);

        $validated['monthly_limit'] = number_format((float) $validated['monthly_limit'], 2, '.', '');

        return $validated;
    }
}
