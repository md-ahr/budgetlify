<?php

use function Pest\Laravel\get;

it('shows the budgets page with demo cards', function () {
    get(route('budgets'))
        ->assertOk()
        ->assertSeeText(__('Budgets'))
        ->assertSeeText(__('Food'))
        ->assertSeeText(__('Travel'));
});
