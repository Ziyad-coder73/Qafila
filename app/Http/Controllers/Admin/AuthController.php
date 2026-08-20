<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check() && (Auth::user()->isAdmin() || Auth::user()->isAgent())) {
            return redirect()->to($this->homeFor(Auth::user()));
        }

        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Invalid credentials.'])->onlyInput('email');
        }

        $user = Auth::user();

        if (! $user->isAdmin() && ! $user->isAgent()) {
            Auth::logout();

            return back()->withErrors(['email' => 'This account does not have admin access.'])->onlyInput('email');
        }

        if ($user->isAgent() && ! $user->is_active) {
            Auth::logout();

            return back()->withErrors(['email' => 'Your agent account has been deactivated.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended($this->homeFor($user));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    private function homeFor($user): string
    {
        return $user->isAdmin() ? route('admin.brands.index') : route('admin.policies.index');
    }
}
