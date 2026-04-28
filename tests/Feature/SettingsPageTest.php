<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

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
        ->assertSeeText(__('Currency'))
        ->assertSeeText(__('Danger zone'));
});
