<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class BudgetController extends Controller
{
    public function index(): View
    {
        return view('budgets.index');
    }
}
