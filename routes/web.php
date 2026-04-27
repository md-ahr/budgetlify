<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/register', [RegisterController::class, 'register'])->name('register')->middleware('guest');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store')->middleware('guest');
Route::get('/login', [SessionController::class, 'login'])->name('login')->middleware('guest');
Route::post('/login', [SessionController::class, 'store'])->name('login.store')->middleware('guest');
Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.request')->middleware('guest');
Route::post('/forgot-password', [AuthController::class, 'forgotPasswordRequest'])->name('password.email')->middleware('guest');
Route::get('/reset-password/{token}', [AuthController::class, 'resetPassword'])->name('password.reset')->middleware('guest');
Route::post('/reset-password', [AuthController::class, 'resetPasswordRequest'])->name('password.update')->middleware('guest');

Route::get('/', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');
Route::post('/logout', [SessionController::class, 'destroy'])->name('logout')->middleware('auth');

Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
Route::get('/budgets', [BudgetController::class, 'index'])->name('budgets');
Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions');
Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
