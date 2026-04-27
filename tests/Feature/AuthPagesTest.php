<?php

use function Pest\Laravel\get;

it('shows the login page', function () {
    get(route('login'))
        ->assertOk()
        ->assertSeeText(__('Log in'))
        ->assertSeeText(__('Welcome back'));
});

it('shows the register page', function () {
    get(route('register'))
        ->assertOk()
        ->assertSeeText(__('Create your account'))
        ->assertSeeText(__('Confirm password'));
});

it('shows the forgot password page', function () {
    get(route('password.request'))
        ->assertOk()
        ->assertSeeText(__('Reset your password'));
});
