<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class SessionController extends Controller
{
    public function login(): View
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $attributes = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', Password::defaults()],
        ]);

        if (! Auth::attempt($attributes)) {
            return back()->withErrors(['password' => 'Invalid credentials']);
        }

        $request->session()->regenerate();

        return to_route('dashboard')->with('success', 'User logged in successfully!');
    }

    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'User logged out successfully!');
    }
}
