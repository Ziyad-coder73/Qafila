<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check() && (Auth::user()->isPartner() || Auth::user()->isAdmin())) {
            return redirect()->route('partner.portal');
        }

        return view('partner.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['username' => 'Invalid credentials.'])->onlyInput('username');
        }

        $user = Auth::user();

        if (! $user->isPartner() && ! $user->isAdmin()) {
            Auth::logout();

            return back()->withErrors(['username' => 'This account does not have partner portal access.'])->onlyInput('username');
        }

        if ($user->isPartner() && ! $user->is_active) {
            Auth::logout();

            return back()->withErrors(['username' => 'Your partner account has been deactivated.'])->onlyInput('username');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('partner.portal'));
    }

    public function magicLogin(string $token)
    {
        $user = User::query()->where('login_token', $token)->where('role', 'partner')->first();

        if (! $user || ! $user->is_active) {
            abort(404);
        }

        Auth::login($user);
        request()->session()->regenerate();

        return redirect()->route('partner.portal');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('partner.login');
    }
}
