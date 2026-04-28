<?php

use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

it('shows the transactions page', function () {
    $user = User::factory()->create();

    actingAs($user);

    get(route('transactions'))
        ->assertOk()
        ->assertSeeText(__('Transactions'));
});

it('lists the authenticated user transactions in the table', function () {
    $user = User::factory()->create();
    Transaction::factory()->for($user)->create([
        'title' => 'Whole Foods',
        'category' => 'Groceries',
        'type' => 'expense',
        'amount' => '84.20',
        'occurred_on' => '2026-04-27',
    ]);
    Transaction::factory()->for($user)->create([
        'title' => 'Salary deposit',
        'category' => 'Salary',
        'type' => 'income',
        'amount' => '4120.00',
        'occurred_on' => '2026-04-26',
    ]);

    actingAs($user);

    get(route('transactions'))
        ->assertOk()
        ->assertSeeText('Whole Foods', false)
        ->assertSeeText('Salary deposit')
        ->assertSeeText(__('Groceries'));
});

it('paginates transactions', function () {
    $user = User::factory()->create();
    Transaction::factory()->count(15)->for($user)->create();

    actingAs($user);

    get(route('transactions', ['per_page' => 10]))
        ->assertOk()
        ->assertSeeText(__('Showing'))
        ->assertSeeTextInOrder(['1', __('to'), '10', __('of'), '15'], false);
});

it('filters transactions by search and category', function () {
    $user = User::factory()->create();
    Transaction::factory()->for($user)->create([
        'title' => 'Coffee Lab',
        'category' => 'Dining',
        'type' => 'expense',
        'amount' => '6.75',
        'occurred_on' => now()->format('Y-m-d'),
    ]);
    Transaction::factory()->for($user)->create([
        'title' => 'Whole Foods',
        'category' => 'Groceries',
        'type' => 'expense',
        'amount' => '84.20',
        'occurred_on' => now()->format('Y-m-d'),
    ]);

    actingAs($user);

    get(route('transactions', ['search' => 'Coffee']))
        ->assertOk()
        ->assertSeeText('Coffee Lab')
        ->assertDontSeeText('Whole Foods');

    get(route('transactions', ['category' => 'Groceries']))
        ->assertOk()
        ->assertSeeText('Whole Foods')
        ->assertDontSeeText('Coffee Lab');
});

it('filters to the last year when date_range is month', function () {
    Carbon::setTestNow('2026-06-15 12:00:00');

    $user = User::factory()->create();
    Transaction::factory()->for($user)->create([
        'title' => 'Within last year',
        'category' => 'Bills',
        'type' => 'expense',
        'amount' => '1.00',
        'occurred_on' => '2026-01-01',
    ]);
    Transaction::factory()->for($user)->create([
        'title' => 'Older than one year',
        'category' => 'Bills',
        'type' => 'expense',
        'amount' => '1.00',
        'occurred_on' => '2024-01-01',
    ]);

    actingAs($user);

    get(route('transactions', ['date_range' => 'month']))
        ->assertOk()
        ->assertSeeText('Within last year')
        ->assertDontSeeText('Older than one year');

    Carbon::setTestNow();
});

it('shows filtered empty state when no rows match', function () {
    $user = User::factory()->create();
    Transaction::factory()->for($user)->create([
        'title' => 'Only One',
        'category' => 'Bills',
        'type' => 'expense',
        'amount' => '10.00',
        'occurred_on' => now()->format('Y-m-d'),
    ]);

    actingAs($user);

    get(route('transactions', ['search' => 'nonexistent-xyz']))
        ->assertOk()
        ->assertSeeText(__('No transactions match your filters.'));
});
