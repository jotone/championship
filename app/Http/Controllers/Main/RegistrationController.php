<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Jobs\SendRegistrationVerificationEmail;
use App\Models\{User, VerifiedEmail};
use App\Traits\SetupVariablesTrait;
use Illuminate\Support\Facades\{Auth, DB};
use Illuminate\View\View;

class RegistrationController extends Controller
{
    use SetupVariablesTrait;

    /**
     * View registration page
     *
     * @return View
     */
    public function index(): View
    {
        return view('main.registration.index', [
            'setup' => $this->settingsData()
        ]);
    }

    /**
     * Create User
     *
     * @param UserStoreRequest $request
     * @return View
     */
    public function store(UserStoreRequest $request): View
    {
        // Create user
        $user = User::create($request->validated());
        // Create verification entity
        VerifiedEmail::create([
            'email' => $user->email,
            'token' => md5(uniqid() . $user->email)
        ]);
        // Send email confirmation
        if (config('app.env') !== 'testing') {
            SendRegistrationVerificationEmail::dispatch($user);
        }
        // Render result page
        return view('main.registration.thank-you', [
            'email' => $user->email,
            'setup' => $this->settingsData()
        ]);
    }

    /**
     * Run user email confirmation
     *
     * @param string $token
     * @return View
     */
    public function confirmation(string $token): View
    {
        // Email verification entity
        $verify = VerifiedEmail::where('token', $token)->firstOrFail();
        // Check the verification token is expired
        $expired = time() > $verify->created_at->addWeek()->timestamp;
        if ($expired) {
            // Update token
            DB::table('verified_emails')
                ->where('email', $verify->email)
                ->update([
                    'token'      => md5($token . $verify->email),
                    'created_at' => now()->format('Y-m-d H:i:s')
                ]);
            // Send new letter with instructions
            if (config('app.env') !== 'testing') {
                SendRegistrationVerificationEmail::dispatch($verify->user);
            }
        } else {
            // Set the user email was verified
            $verify->user->update(['email_verified_at' => now()]);
            // Authorize user
            Auth::loginUsingId($verify->user->id);
        }

        return view('main.registration.confirmation', [
            'expired' => $expired,
            'setup'   => $this->settingsData(),
            'verify'  => $verify
        ]);
    }
}