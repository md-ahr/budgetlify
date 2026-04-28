<?php

namespace App\Models;

use Database\Factories\TransactionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

#[Fillable([
    'title',
    'amount',
    'type',
    'category',
    'occurred_on',
    'notes',
])]
class Transaction extends Model
{
    /** @use HasFactory<TransactionFactory> */
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Sum expense amounts grouped by category for a user and inclusive occurred_on range (Y-m-d).
     * Uses SQL SUM so string-stored amounts aggregate correctly; type is matched case-insensitively.
     * Keys are LOWER(TRIM(category)) so budget cards match spending even with inconsistent casing or whitespace.
     *
     * @return Collection<string, float>
     */
    public static function expenseTotalsByCategoryForUser(int $userId, string $startDate, string $endDate): Collection
    {
        $sumExpression = self::amountSumSqlExpression();

        return self::query()
            ->where('user_id', $userId)
            ->whereRaw('LOWER(TRIM(type)) = ?', ['expense'])
            ->whereNotNull('category')
            ->whereRaw('TRIM(category) != ?', [''])
            ->where('occurred_on', '>=', $startDate)
            ->where('occurred_on', '<=', $endDate)
            ->selectRaw("LOWER(TRIM(category)) as category_key, {$sumExpression} as spent_total")
            ->groupByRaw('LOWER(TRIM(category))')
            ->get()
            ->mapWithKeys(fn ($row) => [$row->category_key => (float) $row->spent_total]);
    }

    /**
     * Sum transaction amounts grouped by calendar month (YYYY-MM) for a user in an inclusive occurred_on range.
     *
     * @param  'expense'|'income'  $typeNormalized
     * @return Collection<string, float>
     */
    public static function sumByMonthForUser(int $userId, string $rangeStartDate, string $rangeEndDate, string $typeNormalized): Collection
    {
        $monthExpr = self::yearMonthBucketSqlExpression();
        $sumExpr = self::amountSumSqlExpression();

        return self::query()
            ->where('user_id', $userId)
            ->whereRaw('LOWER(TRIM(type)) = ?', [$typeNormalized])
            ->whereNotNull('occurred_on')
            ->where('occurred_on', '>=', $rangeStartDate)
            ->where('occurred_on', '<=', $rangeEndDate)
            ->selectRaw("{$monthExpr} as ym, {$sumExpr} as total")
            ->groupByRaw($monthExpr)
            ->orderBy('ym')
            ->get()
            ->mapWithKeys(fn ($row) => [$row->ym => (float) $row->total]);
    }

    /**
     * @param  'expense'|'income'  $typeNormalized
     */
    public static function totalAmountForUserInRange(int $userId, string $startDate, string $endDate, string $typeNormalized): float
    {
        $sumExpr = self::amountSumSqlExpression();
        $value = self::query()
            ->where('user_id', $userId)
            ->whereRaw('LOWER(TRIM(type)) = ?', [$typeNormalized])
            ->whereNotNull('occurred_on')
            ->where('occurred_on', '>=', $startDate)
            ->where('occurred_on', '<=', $endDate)
            ->selectRaw("{$sumExpr} as total")
            ->value('total');

        return (float) ($value ?? 0);
    }

    /**
     * Normalized key for matching budget categories to {@see expenseTotalsByCategoryForUser()} totals.
     */
    public static function normalizedCategoryKeyForBudgetLookup(string $category): string
    {
        return mb_strtolower(trim($category));
    }

    /**
     * Earliest `occurred_on` date (Y-m-d) for a preset, or null when no cutoff applies.
     */
    public static function minimumOccurredOnForRange(string $range): ?string
    {
        if ($range === 'all') {
            return null;
        }

        $now = now();

        return match ($range) {
            '7d' => $now->copy()->subDays(7)->toDateString(),
            '30d' => $now->copy()->subDays(30)->toDateString(),
            'month' => $now->copy()->subYear()->toDateString(),
            default => null,
        };
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeWithTitleSearch(Builder $query, string $term): Builder
    {
        if ($term === '') {
            return $query;
        }

        $pattern = '%'.addcslashes($term, '%_\\').'%';

        return $query->where('title', 'like', $pattern);
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeForCategory(Builder $query, string $category): Builder
    {
        if ($category === '') {
            return $query;
        }

        return $query->where('category', $category);
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeWithOccurredOnFrom(Builder $query, ?string $minDate): Builder
    {
        if ($minDate === null) {
            return $query;
        }

        return $query->where('occurred_on', '>=', $minDate);
    }

    /**
     * Stable ordering for paginated listings (newest activity first).
     *
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeLatestFirst(Builder $query): Builder
    {
        return $query->orderByDesc('occurred_on')->orderByDesc('id');
    }

    private static function amountSumSqlExpression(): string
    {
        return match (self::query()->getConnection()->getDriverName()) {
            'sqlite' => 'SUM(CAST(amount AS REAL))',
            'pgsql' => 'SUM(CAST(amount AS NUMERIC))',
            default => 'SUM(CAST(amount AS DECIMAL(12, 2)))',
        };
    }

    private static function yearMonthBucketSqlExpression(): string
    {
        return match (self::query()->getConnection()->getDriverName()) {
            'sqlite' => "strftime('%Y-%m', occurred_on)",
            'pgsql' => "to_char(occurred_on::date, 'YYYY-MM')",
            default => "DATE_FORMAT(occurred_on, '%Y-%m')",
        };
    }
}
