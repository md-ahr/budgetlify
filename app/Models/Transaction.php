<?php

namespace App\Models;

use Database\Factories\TransactionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
