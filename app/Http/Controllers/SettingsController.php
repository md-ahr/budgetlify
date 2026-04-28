<?php

namespace App\Http\Controllers;

use App\Support\SupportedCurrencies;
use App\Support\SupportedDateFormats;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        return view('settings.index', [
            'user' => Auth::user(),
            'currencies' => SupportedCurrencies::options(),
            'dateFormats' => SupportedDateFormats::options(),
        ]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
        ]);

        $user->update($validated);

        return redirect()->route('settings')->with('status', __('Profile saved.'));
    }

    public function updatePreferences(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'currency' => ['required', 'string', Rule::in(SupportedCurrencies::CODES)],
            'date_format' => ['required', 'string', Rule::in(SupportedDateFormats::keys())],
        ]);

        Auth::user()->update([
            'currency' => $validated['currency'],
            'date_format' => $validated['date_format'],
        ]);

        return redirect()->route('settings')->with('status', __('Preferences saved.'));
    }

    public function destroyAccount(Request $request): RedirectResponse
    {
        $request->validate([
            'confirm_delete' => ['required', 'accepted'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', __('Your account has been deleted.'));
    }
}
