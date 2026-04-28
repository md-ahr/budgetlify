<?php

use App\Models\Transaction;
use App\Models\User;
use App\Support\Money;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

uses(RefreshDatabase::class);

it('persists transaction amount in base currency when user enters BDT', function () {
    $this->setExchangeRatesForTesting(['bdt' => 100.0]);

    $user = User::factory()->create(['currency' => 'BDT']);

    actingAs($user);

    post(route('transactions.store'), [
        'title' => 'Market',
        'amount' => '30000',
        'type' => 'expense',
        'category' => 'Groceries',
        'occurred_on' => '2026-06-01',
        'notes' => null,
    ])->assertRedirect(route('transactions'));

    $tx = Transaction::query()->first();
    expect($tx)->not->toBeNull();
    expect((float) $tx->amount)->toBe(300.0);
});

it('shows roughly the entered BDT amount on the transactions page', function () {
    $this->setExchangeRatesForTesting(['bdt' => 100.0]);

    $user = User::factory()->create(['currency' => 'BDT']);
    Transaction::factory()->for($user)->create([
        'title' => 'Shop',
        'amount' => '300.00',
        'type' => 'expense',
        'category' => 'Shopping',
        'occurred_on' => '2026-06-10',
    ]);

    actingAs($user);

    get(route('transactions'))
        ->assertOk()
        ->assertSeeText(Money::format(300.0, 2));
});
