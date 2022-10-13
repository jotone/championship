<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\{PasswordResetRequest, PasswordResetoreRequest};
use App\Jobs\SendPasswordResetEmail;
use App\Models\{PasswordReset, User};
use Illuminate\View\View;

class PasswordResetController extends Controller
{
    /**
     * View password restoration request form
     *
     * @return View
     */
    public function index(): View
    {
        return view('main.password-reset.index');
    }

    /**
     * Reset password form
     *
     * @param string $token
     * @return View
     */
    public function form(string $token): View
    {
        return view('main.password-reset.form', [
            'reset'  => PasswordReset::where('token', $token)->firstOrFail()
        ]);
    }

    /**
     * Send password reset instructions to the user's email
     *
     * @param PasswordResetoreRequest $request
     * @return View
     */
    public function store(PasswordResetoreRequest $request): View
    {
        // Request data
        $args = $request->validated();
        // Retrieve user
        $user = User::firstWhere('email', $args['email']);
        // Check user exists
        if (!$user) {
            return view('main.password-reset.report', [
                'message' => 'User with such email has not been found.'
            ]);
        }
        // Fill password reset entity
        if (empty($user->passwordReset)) {
            PasswordReset::create($args);
        } else {
            // Update token
            DB::table('password_resets')
                ->where('email', $args['email'])
                ->update([
                    'token' => md5(uniqid() . $user->email)
                ]);
        }
        // Send email with instructions
        if (config('app.env') !== 'testing') {
            SendPasswordResetEmail::dispatch($user);
        }
        // Render result page
        return view('main.password-reset.report', [
            'message' => 'The letter with the further instructions was send to your email: ' . $args['email'] . '.'
        ]);
    }

    /**
     * Modify user's password
     *
     * @param string $token
     * @param PasswordResetRequest $request
     * @return View
     */
    public function update(string $token, PasswordResetRequest $request): View
    {
        $reset = PasswordReset::where('token', $token)->firstOrFail();

        $args = $request->validated();

        $reset->user->password = $args['password'];
        $reset->user->save();

        DB::delete('DELETE FROM password_resets WHERE email = ?', [$reset->email]);

        return view('main.password-reset.success');
    }
}