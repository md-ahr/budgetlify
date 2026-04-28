<?php

use App\Models\Budget;
use App\Models\Transaction;
use App\Models\User;
use App\Support\Money;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

uses(RefreshDatabase::class);

it('shows the budgets page for authenticated users', function () {
    $user = User::factory()->create();
    Budget::factory()->for($user)->create([
        'name' => 'Groceries plan',
        'category' => 'Groceries',
        'monthly_limit' => '500.00',
    ]);

    actingAs($user);

    get(route('budgets'))
        ->assertOk()
        ->assertSeeText(__('Budgets'))
        ->assertSeeText('Groceries plan')
        ->assertSeeText(__('Category: :cat', ['cat' => 'Groceries']));
});

it('stores a budget', function () {
    $user = User::factory()->create();

    actingAs($user);

    post(route('budgets.store'), [
        'name' => 'My cap',
        'category' => 'Bills',
        'monthly_limit' => 200,
    ])->assertRedirect(route('budgets'));

    expect(Budget::query()->where('user_id', $user->id)->count())->toBe(1);
    expect(Budget::first()->monthly_limit)->toBe('200.00');
});

it('counts expense totals case-insensitively on type', function () {
    Carbon::setTestNow('2026-06-15 12:00:00');

    $user = User::factory()->create();
    Budget::factory()->for($user)->create([
        'name' => 'Shop',
        'category' => 'Shopping',
        'monthly_limit' => '1000.00',
    ]);
    Transaction::factory()->for($user)->create([
        'type' => 'Expense',
        'category' => 'Shopping',
        'amount' => '800.00',
        'occurred_on' => '2026-06-10',
    ]);

    actingAs($user);

    get(route('budgets'))
        ->assertOk()
        ->assertSeeText(Money::format(800.0, 2));

    Carbon::setTestNow();
});

it('displays amounts in the user display currency', function () {
    Carbon::setTestNow('2026-06-15 12:00:00');

    $user = User::factory()->create(['currency' => 'EUR']);
    Budget::factory()->for($user)->create([
        'name' => 'Shop',
        'category' => 'Shopping',
        'monthly_limit' => '1000.00',
    ]);
    Transaction::factory()->for($user)->create([
        'type' => 'expense',
        'category' => 'Shopping',
        'amount' => '50.00',
        'occurred_on' => '2026-06-10',
    ]);

    actingAs($user);

    get(route('budgets'))
        ->assertOk()
        ->assertSeeText(Money::format(50.0, 2));

    Carbon::setTestNow();
});

it('matches spending when transaction category casing differs from the budget', function () {
    Carbon::setTestNow('2026-06-15 12:00:00');

    $user = User::factory()->create();
    Budget::factory()->for($user)->create([
        'name' => 'Shop',
        'category' => 'Shopping',
        'monthly_limit' => '1000.00',
    ]);
    Transaction::factory()->for($user)->create([
        'type' => 'expense',
        'category' => 'shopping',
        'amount' => '50.00',
        'occurred_on' => '2026-06-10',
    ]);

    actingAs($user);

    get(route('budgets'))
        ->assertOk()
        ->assertSeeText(Money::format(50.0, 2));

    Carbon::setTestNow();
});

it('shows spent from expense transactions in the current month', function () {
    Carbon::setTestNow('2026-06-15 12:00:00');

    $user = User::factory()->create();
    Budget::factory()->for($user)->create([
        'name' => 'Food',
        'category' => 'Dining',
        'monthly_limit' => '100.00',
    ]);
    Transaction::factory()->for($user)->create([
        'type' => 'expense',
        'category' => 'Dining',
        'amount' => '40.50',
        'occurred_on' => '2026-06-10',
    ]);

    actingAs($user);

    get(route('budgets'))
        ->assertOk()
        ->assertSeeText(Money::format(40.5, 2));

    Carbon::setTestNow();
});
