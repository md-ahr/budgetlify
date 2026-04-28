<?php

use App\Models\Transaction;
use App\Models\User;
use App\Support\Money;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

it('redirects guests from analytics to login', function () {
    get(route('analytics'))
        ->assertRedirect(route('login'));
});

it('shows the analytics page', function () {
    actingAs(User::factory()->create());

    get(route('analytics'))
        ->assertOk()
        ->assertSeeText(__('Analytics'))
        ->assertSeeText(__('Monthly spending'));
});

it('shows this month income and expense totals from transactions', function () {
    Carbon::setTestNow('2026-06-15 12:00:00');

    $user = User::factory()->create();
    Transaction::factory()->for($user)->create([
        'type' => 'income',
        'amount' => '1000.00',
        'category' => 'Salary',
        'occurred_on' => '2026-06-01',
    ]);
    Transaction::factory()->for($user)->create([
        'type' => 'expense',
        'amount' => '250.25',
        'category' => 'Groceries',
        'occurred_on' => '2026-06-10',
    ]);

    actingAs($user);

    get(route('analytics'))
        ->assertOk()
        ->assertSeeText(Money::format(1000.0, 2))
        ->assertSeeText(Money::format(250.25, 2));

    Carbon::setTestNow();
});
