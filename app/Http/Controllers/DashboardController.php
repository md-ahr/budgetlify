<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\ExchangeRateService;
use App\Support\Money;
use App\Support\UserDate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $userId = (int) $user->id;
        $now = Carbon::now();

        $totalIncomeAll = Transaction::totalAmountForUserAllTime($userId, 'income');
        $totalExpenseAll = Transaction::totalAmountForUserAllTime($userId, 'expense');
        $totalBalance = $totalIncomeAll - $totalExpenseAll;

        $monthStart = $now->copy()->startOfMonth()->toDateString();
        $monthEnd = $now->copy()->endOfMonth()->toDateString();
        $monthlyIncome = Transaction::totalAmountForUserInRange($userId, $monthStart, $monthEnd, 'income');
        $monthlyExpense = Transaction::totalAmountForUserInRange($userId, $monthStart, $monthEnd, 'expense');

        $savingsRate = null;
        if ($monthlyIncome > 0.01) {
            $savingsRate = min(100, max(0, (int) round((($monthlyIncome - $monthlyExpense) / $monthlyIncome) * 100)));
        }

        $expenseRanges = $this->buildExpenseRanges($userId, $now);

        $displayCurrency = Money::currencyCode();
        $exchange = app(ExchangeRateService::class);
        foreach ($expenseRanges as $key => $bundle) {
            $expenseRanges[$key]['values'] = array_map(
                fn (float $v): float => round($exchange->fromBaseTo($v, $displayCurrency), 2),
                $bundle['values'],
            );
        }

        $recent = $user->transactions()->latestFirst()->limit(5)->get();
        $recentTransactions = $recent->map(fn ($t): array => [
            'title' => (string) $t->title,
            'category' => trim((string) ($t->category ?? '')),
            'date' => $this->occurrenceLabel($t->occurred_on),
            'amount' => (float) $t->amount,
            'income' => mb_strtolower(trim((string) $t->type)) === 'income',
        ])->all();

        return view('dashboard.index', [
            'totalBalance' => $totalBalance,
            'monthlyIncome' => $monthlyIncome,
            'monthlyExpense' => $monthlyExpense,
            'savingsRate' => $savingsRate,
            'expenseRanges' => $expenseRanges,
            'recentTransactions' => $recentTransactions,
        ]);
    }

    /**
     * @return array<string, array{labels: list<string>, values: list<float>, hint: string}>
     */
    private function buildExpenseRanges(int $userId, Carbon $now): array
    {
        $dayStart = $now->copy()->subDays(6)->toDateString();
        $dayEnd = $now->toDateString();
        $byDay = Transaction::expenseTotalsByDayForUser($userId, $dayStart, $dayEnd);

        $labels7d = [];
        $values7d = [];
        for ($i = 0; $i < 7; $i++) {
            $d = $now->copy()->subDays(6 - $i);
            $key = $d->toDateString();
            $labels7d[] = $d->isoFormat('ddd');
            $values7d[] = round((float) $byDay->get($key, 0.0), 2);
        }

        $values30d = [];
        for ($w = 0; $w < 4; $w++) {
            $rangeStart = $now->copy()->subDays(27 - ($w * 7))->toDateString();
            $rangeEnd = $now->copy()->subDays(21 - ($w * 7))->toDateString();
            $values30d[] = round(
                Transaction::totalAmountForUserInRange($userId, $rangeStart, $rangeEnd, 'expense'),
                2,
            );
        }

        $rangeStartYear = $now->copy()->subMonths(11)->startOfMonth()->toDateString();
        $rangeEndYear = $now->copy()->endOfMonth()->toDateString();
        $byMonth = Transaction::sumByMonthForUser($userId, $rangeStartYear, $rangeEndYear, 'expense');

        $labels365 = [];
        $values365 = [];
        for ($i = 0; $i < 12; $i++) {
            $ym = $now->copy()->subMonths(11 - $i)->format('Y-m');
            $labels365[] = UserDate::formatMonthYear(Carbon::parse($ym.'-01'));
            $values365[] = round((float) $byMonth->get($ym, 0.0), 2);
        }

        return [
            '7d' => [
                'labels' => $labels7d,
                'values' => $values7d,
                'hint' => __('Last 7 days — daily spend'),
            ],
            '30d' => [
                'labels' => [__('Week 1'), __('Week 2'), __('Week 3'), __('Week 4')],
                'values' => $values30d,
                'hint' => __('Last 28 days — weekly spend'),
            ],
            '365d' => [
                'labels' => $labels365,
                'values' => $values365,
                'hint' => __('Last 12 months — monthly spend'),
            ],
        ];
    }

    private function occurrenceLabel(?string $occurredOn): string
    {
        if ($occurredOn === null || $occurredOn === '') {
            return '';
        }

        $d = Carbon::parse($occurredOn)->startOfDay();
        $today = Carbon::now()->startOfDay();
        if ($d->equalTo($today)) {
            return __('Today');
        }
        if ($d->equalTo($today->copy()->subDay())) {
            return __('Yesterday');
        }

        return UserDate::format($occurredOn);
    }
}
