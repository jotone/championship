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
        // Get request data
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Search user
        $user = User::firstWhere('email', $credentials['email']);

        // Check if user verified his email
        if ($user && empty($user->email_verified_at)) {
            return back()
                ->withErrors(['email' => 'Ваш email ще не підтверджено.'])
                ->onlyInput('email');
        }

        // Run authorization
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            // Create login history record
            LoginHistory::create([
                'user_id'    => $user->id,
                'ip'         => $request->ips(),
                'user_agent' => $request->userAgent()
            ]);
            // Set jwt to session
            Session::put('jwt-token', auth('api')->login($user));
            // Regenerate session data
            $request->session()->regenerate();

            return redirect()->back();
        }

        return back()
            ->withErrors(['email' => 'Дані введені вами не відповідають жодному з наших записів.'])
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
        // Sign Out
        Auth::logout();
        // Invalidate jwt session
        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('home.index');
    }
}