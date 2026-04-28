<?php

namespace App\Providers;

use App\Support\Money;
use App\Support\UserDate;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useTailwind();

        View::composer('*', function (\Illuminate\View\View $view): void {
            $view->with(
                'money',
                static fn (float|int $amount, ?int $decimals = 2): string => Money::format($amount, $decimals),
            );
            $view->with(
                'formatUserDate',
                static fn (Carbon|DateTimeInterface|string $value): string => UserDate::format($value),
            );
            $view->with(
                'formatUserMonthYear',
                static fn (Carbon|DateTimeInterface|string $value): string => UserDate::formatMonthYear($value),
            );
        });
    }
}
