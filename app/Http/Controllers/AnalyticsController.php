<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function index(): View
    {
        return view('analytics.index');
    }
}
