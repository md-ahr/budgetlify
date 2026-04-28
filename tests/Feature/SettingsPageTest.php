<?php

use App\Models\Budget;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertGuest;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\patch;

uses(RefreshDatabase::class);

it('redirects guests from settings to login', function () {
    get(route('settings'))
        ->assertRedirect(route('login'));
});

it('shows the settings page', function () {
    actingAs(User::factory()->create());

    get(route('settings'))
        ->assertOk()
        ->assertSeeText(__('Settings'))
        ->assertSeeText(__('Profile'))
        ->assertSeeText(__('Regional formats'))
        ->assertSeeText(__('Appearance'))
        ->assertSeeText(__('Danger zone'))
        ->assertDontSeeText(__('Notifications'));
});

it('updates profile', function () {
    $user = User::factory()->create([
        'name' => 'Original',
        'email' => 'original@example.com',
    ]);

    actingAs($user);

    patch(route('settings.profile.update'), [
        'name' => 'Updated Name',
        'email' => 'updated@example.com',
    ])
        ->assertRedirect(route('settings'))
        ->assertSessionHas('status');

    expect($user->fresh())
        ->name->toBe('Updated Name')
        ->email->toBe('updated@example.com');
});

it('updates display currency and date format', function () {
    $user = User::factory()->create([
        'currency' => 'USD',
        'date_format' => 'locale_long_us',
    ]);

    actingAs($user);

    patch(route('settings.preferences.update'), [
        'currency' => 'BDT',
        'date_format' => 'iso',
    ])
        ->assertRedirect(route('settings'))
        ->assertSessionHas('status');

    expect($user->fresh())
        ->currency->toBe('BDT')
        ->date_format->toBe('iso');
});

it('validates profile email uniqueness', function () {
    User::factory()->create(['email' => 'taken@example.com']);
    $user = User::factory()->create(['email' => 'mine@example.com']);

    actingAs($user);

    patch(route('settings.profile.update'), [
        'name' => $user->name,
        'email' => 'taken@example.com',
    ])->assertSessionHasErrors('email');
});

it('validates currency', function () {
    actingAs(User::factory()->create());

    patch(route('settings.preferences.update'), [
        'currency' => 'XYZ',
        'date_format' => 'iso',
    ])
        ->assertSessionHasErrors('currency');
});

it('validates date format', function () {
    actingAs(User::factory()->create());

    patch(route('settings.preferences.update'), [
        'currency' => 'USD',
        'date_format' => 'not_a_real_format',
    ])
        ->assertSessionHasErrors('date_format');
});

it('redirects guests when deleting account', function () {
    delete(route('settings.account.destroy'), ['confirm_delete' => '1'])
        ->assertRedirect(route('login'));
});

it('requires confirmation to delete account', function () {
    $user = User::factory()->create();

    actingAs($user);

    delete(route('settings.account.destroy'), [])
        ->assertSessionHasErrors('confirm_delete');

    expect(User::query()->whereKey($user->id)->exists())->toBeTrue();
});

it('deletes account and cascades related data', function () {
    $user = User::factory()->create();
    $transaction = Transaction::factory()->for($user)->create();
    $budget = Budget::factory()->for($user)->create();

    actingAs($user);

    delete(route('settings.account.destroy'), ['confirm_delete' => '1'])
        ->assertRedirect(route('login'))
        ->assertSessionHas('status');

    assertGuest();

    expect(User::query()->whereKey($user->id)->exists())->toBeFalse();
    expect(Transaction::query()->whereKey($transaction->id)->exists())->toBeFalse();
    expect(Budget::query()->whereKey($budget->id)->exists())->toBeFalse();
});
