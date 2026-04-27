<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'login'])->name('login');

Route::get('/register', [AuthController::class, 'register'])->name('register');

Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.request');

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');

Route::get('/budgets', [BudgetController::class, 'index'])->name('budgets');

Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions');

Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
