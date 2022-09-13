<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\{LoginHistory, User};
use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Support\Facades\{Auth, Session};

class AuthController extends Controller
{
    /**
     * Logging in
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::firstWhere('email', $credentials['email']);

        if ($user && empty($user->email_verified_at)) {
            return back()
                ->withErrors(['email' => 'You have not verified your email yet.'])
                ->onlyInput('email');
        }

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            LoginHistory::create([
                'user_id'    => $user->id,
                'ip'         => $request->ips(),
                'user_agent' => $request->userAgent()
            ]);
            Session::put('jwt-token', auth('api')->login($user));
            $request->session()->regenerate();

            return redirect()->intended();
        }

        return back()
            ->withErrors(['email' => 'The provided credentials do not match our records.'])
            ->onlyInput('email');
    }

    /**
     * Log the user out of the application.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('home.index');
    }
}