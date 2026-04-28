<?php

use App\Models\Transaction;
use App\Models\User;
use App\Support\Money;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

it('redirects guests from the dashboard to login', function () {
    get(route('dashboard'))
        ->assertRedirect(route('login'));
});

it('shows the dashboard for authenticated users', function () {
    actingAs(User::factory()->create());

    get(route('dashboard'))
        ->assertOk()
        ->assertSeeText(__('Expense overview'))
        ->assertSeeText(__('Recent transactions'));
});

it('shows totals and recent transactions from the database', function () {
    Carbon::setTestNow('2026-06-15 12:00:00');

    $user = User::factory()->create();
    Transaction::factory()->for($user)->create([
        'title' => 'Salary line',
        'type' => 'income',
        'amount' => '5000.00',
        'category' => 'Salary',
        'occurred_on' => '2026-06-10',
    ]);
    Transaction::factory()->for($user)->create([
        'title' => 'Grocery run',
        'type' => 'expense',
        'amount' => '100.00',
        'category' => 'Groceries',
        'occurred_on' => '2026-06-14',
    ]);

    actingAs($user);

    get(route('dashboard'))
        ->assertOk()
        ->assertSeeText('Salary line')
        ->assertSeeText('Grocery run')
        ->assertSeeText(Money::format(4900.0, 2))
        ->assertSee(route('transactions'));

    Carbon::setTestNow();
});

it('shows an empty state when there are no transactions', function () {
    actingAs(User::factory()->create());

    get(route('dashboard'))
        ->assertOk()
        ->assertSeeText(__('No transactions yet. Add one from the transactions page.'));
});
