<?php

use function Pest\Laravel\get;

it('shows the settings page', function () {
    get(route('settings'))
        ->assertOk()
        ->assertSeeText(__('Settings'))
        ->assertSeeText(__('Profile'))
        ->assertSeeText(__('Currency'))
        ->assertSeeText(__('Danger zone'));
});
