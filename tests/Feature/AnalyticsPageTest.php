<?php

use function Pest\Laravel\get;

it('shows the analytics page', function () {
    get(route('analytics'))
        ->assertOk()
        ->assertSeeText(__('Analytics'))
        ->assertSeeText(__('Monthly spending'));
});
