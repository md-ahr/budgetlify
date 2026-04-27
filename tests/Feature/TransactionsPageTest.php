<?php

use function Pest\Laravel\get;

it('shows the transactions page', function () {
    get(route('transactions'))
        ->assertOk()
        ->assertSeeText(__('Transactions'))
        ->assertSeeText(__('Whole Foods'), false);
});

it('lists static demo transactions in the table', function () {
    get(route('transactions'))
        ->assertOk()
        ->assertSeeText('Salary deposit')
        ->assertSeeText(__('Groceries'));
});
