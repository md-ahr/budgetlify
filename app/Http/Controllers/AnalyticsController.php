<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\ExchangeRateService;
use App\Support\FinanceCategories;
use App\Support\Money;
use App\Support\UserDate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    /** @var list<string> */
    private const CATEGORY_SWATCHES = [
        'bg-brand',
        'bg-accent',
        'bg-indigo-400',
        'bg-amber-400',
        'bg-sky-400',
        'bg-slate-300 dark:bg-slate-600',
    ];

    /** @var list<string> */
    private const CATEGORY_CHART_COLORS = [
        'rgba(79, 70, 229, 0.92)',
        'rgba(34, 197, 94, 0.92)',
        'rgba(129, 140, 248, 0.92)',
        'rgba(251, 191, 36, 0.92)',
        'rgba(56, 189, 248, 0.9)',
        'rgba(148, 163, 184, 0.88)',
    ];

    public function index(): View
    {
        $user = Auth::user();
        $now = now();
        $rangeStart = $now->copy()->subMonths(5)->startOfMonth()->toDateString();
        $rangeEnd = $now->copy()->endOfMonth()->toDateString();

        $monthKeys = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthKeys[] = $now->copy()->subMonths($i)->format('Y-m');
        }

        $expenseByMonth = Transaction::sumByMonthForUser($user->id, $rangeStart, $rangeEnd, 'expense');
        $incomeByMonth = Transaction::sumByMonthForUser($user->id, $rangeStart, $rangeEnd, 'income');

        $monthLabels = [];
        $monthlySpend = [];
        foreach ($monthKeys as $ym) {
            $monthLabels[] = UserDate::formatMonthYear(Carbon::parse($ym.'-01'));
            $monthlySpend[] = round($expenseByMonth->get($ym, 0.0), 2);
        }

        $monthlyTrend = [];
        foreach ($monthlySpend as $idx => $val) {
            $monthlyTrend[] = $idx === 0 ? $val : round($monthlySpend[$idx - 1], 2);
        }

        $categoryTotals = Transaction::expenseTotalsByCategoryForUser($user->id, $rangeStart, $rangeEnd);
        $categoryGrandTotal = $categoryTotals->sum();

        $sortedCategories = $categoryTotals->sortDesc();
        $categoryRows = [];
        $categoryChartLabels = [];
        $categoryChartValues = [];
        $categoryChartColors = [];

        if ($categoryGrandTotal < 0.01) {
            $categoryRows[] = [
                'label' => __('No expenses in this range'),
                'amount' => 0.0,
                'pct' => 0,
                'swatch' => 'bg-slate-300 dark:bg-slate-600',
            ];
            $categoryChartLabels = [__('No expenses in this range')];
            $categoryChartValues = [1.0];
            $categoryChartColors = ['rgba(148, 163, 184, 0.35)'];
        } else {
            $top = $sortedCategories->take(5);
            $otherSum = (float) $sortedCategories->slice(5)->sum();
            $idx = 0;
            foreach ($top as $normKey => $amount) {
                $label = $this->displayCategoryLabel((string) $normKey);
                $pct = (int) round(((float) $amount / $categoryGrandTotal) * 100);
                $swatch = self::CATEGORY_SWATCHES[$idx % count(self::CATEGORY_SWATCHES)];
                $color = self::CATEGORY_CHART_COLORS[$idx % count(self::CATEGORY_CHART_COLORS)];
                $categoryRows[] = [
                    'label' => $label,
                    'amount' => round((float) $amount, 2),
                    'pct' => $pct,
                    'swatch' => $swatch,
                ];
                $categoryChartLabels[] = $label;
                $categoryChartValues[] = round((float) $amount, 2);
                $categoryChartColors[] = $color;
                $idx++;
            }
            if ($otherSum >= 0.01) {
                $swatch = self::CATEGORY_SWATCHES[5];
                $color = self::CATEGORY_CHART_COLORS[5];
                $pct = (int) round(($otherSum / $categoryGrandTotal) * 100);
                $categoryRows[] = [
                    'label' => __('Other'),
                    'amount' => round($otherSum, 2),
                    'pct' => $pct,
                    'swatch' => $swatch,
                ];
                $categoryChartLabels[] = __('Other');
                $categoryChartValues[] = round($otherSum, 2);
                $categoryChartColors[] = $color;
            }
        }

        $monthStart = $now->copy()->startOfMonth()->toDateString();
        $monthEnd = $now->copy()->endOfMonth()->toDateString();
        $incomeThisMonth = Transaction::totalAmountForUserInRange($user->id, $monthStart, $monthEnd, 'income');
        $expenseThisMonth = Transaction::totalAmountForUserInRange($user->id, $monthStart, $monthEnd, 'expense');
        $netThisMonth = $incomeThisMonth - $expenseThisMonth;

        $expenseBarPct = $incomeThisMonth > 0.01
            ? min(100, (int) round(($expenseThisMonth / $incomeThisMonth) * 100))
            : ($expenseThisMonth > 0.01 ? 100 : 0);

        $savingsCumulative = [];
        $running = 0.0;
        foreach ($monthKeys as $ym) {
            $running += $incomeByMonth->get($ym, 0.0) - $expenseByMonth->get($ym, 0.0);
            $savingsCumulative[] = round($running, 2);
        }

        $savingsLabels = $monthLabels;
        $lastYm = $monthKeys[array_key_last($monthKeys)];
        $lastMonthNet = $incomeByMonth->get($lastYm, 0.0) - $expenseByMonth->get($lastYm, 0.0);

        $savingsBadgeLabel = $lastMonthNet >= 0 ? __('On track') : __('Watch spending');
        $savingsBadgeClass = $lastMonthNet >= 0
            ? 'bg-accent-muted text-accent-dark dark:bg-emerald-950/40 dark:text-emerald-300'
            : 'bg-amber-50 text-amber-900 dark:bg-amber-950/40 dark:text-amber-200';

        $displayCurrency = Money::currencyCode();
        $exchange = app(ExchangeRateService::class);
        $toDisplay = fn (float $v): float => round($exchange->fromBaseTo($v, $displayCurrency), 2);
        $mapSeries = fn (array $xs): array => array_map(fn ($v) => $toDisplay((float) $v), $xs);

        $monthlySpend = $mapSeries($monthlySpend);
        $monthlyTrend = $mapSeries($monthlyTrend);
        $categoryChartValues = $mapSeries($categoryChartValues);
        $flowValues = [$toDisplay($incomeThisMonth), $toDisplay($expenseThisMonth)];
        $savingsCumulative = $mapSeries($savingsCumulative);

        return view('analytics.index', [
            'monthLabels' => $monthLabels,
            'monthlySpend' => $monthlySpend,
            'monthlyTrend' => $monthlyTrend,
            'categoryRows' => $categoryRows,
            'categoryChartLabels' => $categoryChartLabels,
            'categoryChartValues' => $categoryChartValues,
            'categoryChartColors' => $categoryChartColors,
            'categoryGrandTotal' => $categoryGrandTotal,
            'analyticsRangeLabel' => __('Last :count months', ['count' => 6]),
            'flowLabels' => [__('Income'), __('Expenses')],
            'flowValues' => $flowValues,
            'incomeThisMonth' => $incomeThisMonth,
            'expenseThisMonth' => $expenseThisMonth,
            'netThisMonth' => $netThisMonth,
            'expenseBarPct' => $expenseBarPct,
            'flowPeriodLabel' => UserDate::formatMonthYear($now),
            'savingsLabels' => $savingsLabels,
            'savingsCumulative' => $savingsCumulative,
            'savingsBadgeLabel' => $savingsBadgeLabel,
            'savingsBadgeClass' => $savingsBadgeClass,
        ]);
    }

    private function displayCategoryLabel(string $normalizedKey): string
    {
        foreach (FinanceCategories::ALL as $cat) {
            if (mb_strtolower($cat) === $normalizedKey) {
                return $cat;
            }
        }

        return mb_convert_case(str_replace('_', ' ', $normalizedKey), MB_CASE_TITLE, 'UTF-8');
    }
}
