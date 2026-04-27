<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;

uses(RefreshDatabase::class);

use function Pest\Laravel\get;
use function Pest\Laravel\post;

it('shows the reset password form with email from the query string', function () {
    get(route('password.reset', ['token' => 'test-token', 'email' => 'user@example.com']))
        ->assertOk()
        ->assertSeeText(__('Choose a new password'))
        ->assertSee('value="user@example.com"', false);
});

it('sends a password reset notification for a known email', function () {
    Notification::fake();

    $user = User::factory()->create();

    post(route('password.email'), ['email' => $user->email])
        ->assertRedirect()
        ->assertSessionHas('status', __('passwords.sent'));

    Notification::assertSentTo($user, ResetPassword::class);
});

it('resets the password with a valid token', function () {
    $user = User::factory()->create(['password' => Hash::make('old-secret')]);
    $token = Password::createToken($user);

    post(route('password.update'), [
        'email' => $user->email,
        'token' => $token,
        'password' => 'new-secret-password',
        'password_confirmation' => 'new-secret-password',
    ])
        ->assertRedirect(route('login'))
        ->assertSessionHas('status', __('passwords.reset'));

    $user->refresh();

    expect(Hash::check('new-secret-password', $user->password))->toBeTrue();
});
