<?php

namespace App\Http\Controllers\Api;

use App\Classes\FileHelper;
use App\Http\Controllers\BasicApiController;
use App\Http\Requests\UserStoreRequest;
use App\Jobs\SendRegistrationVerificationEmail;
use App\Models\{Settings, User, VerifiedEmail};
use App\Rules\AlreadyExists;
use Illuminate\Http\{Request, Response};
use Illuminate\Support\Facades\{Auth, Validator};

class UsersController extends BasicApiController
{
    /**
     * Get user list
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        return $this->renderIndexPage($request, User::class, function ($content, $search) {
            return $content->where('name', 'like', '%' . $search . '%')->orWhere('email', 'like', '%' . $search . '%');
        });
    }

    /**
     * View user data
     *
     * @param int $user_id
     * @param Request $request
     * @return Response
     */
    public function show(int $user_id, Request $request): Response
    {
        $user = User::query();
        if ($request->has('select')) {
            $user = $user->select(explode(',', $request->get('select')));
        }

        if ($request->has('with')) {
            $user = $user->with($request->get('with'));
        }

        return response($user->findOrFail($user_id));
    }

    /**
     * Create user
     *
     * @param UserStoreRequest $request
     * @return Response
     * @throws \ImagickException
     */
    public function store(UserStoreRequest $request): Response
    {
        // Check the free registration is enabled
        $registration_enable = Settings::firstWhere('key', 'registration_enable');
        // Additional arguments
        $args = [
            'created_by'        => Auth::id(),
            'email_verified_at' => $registration_enable->converted_value ? null : now()
        ];
        // Create user
        $user = User::create(array_merge($request->validated(), $args));
        // Check img_url file exists
        if ($request->hasFile('img_url')) {
            try {
                // Attempt to save file
                $user->img_url = FileHelper::saveFile(
                    $request->file('img_url'),
                    'uploads/users/' . $user->id,
                    'user_img'
                );
            } catch (\Exception $e) {
                return response(['errors' => [
                    'img_url' => [$e->getMessage()]
                ]], 400);
            }
        } else {
            $user->img_url = '/images/noname_big.jpg';
        }
        $user->save();

        if ($registration_enable->converted_value) {
            // Create verification entity
            VerifiedEmail::create([
                'email' => $user->email,
                'token' => md5(uniqid() . $user->email)
            ]);
            // Send email confirmation
            if (config('app.env') !== 'testing') {
                SendRegistrationVerificationEmail::dispatch($user);
            }
        }

        return response($user, 201);
    }

    /**
     * Update user
     *
     * @param User $user
     * @param Request $request
     * @return Response
     */
    public function update(User $user, Request $request): Response
    {
        $args = $request->only(['name', 'email', 'img_url', 'password', 'confirmation', 'role_id']);
        $rules = [];
        foreach ($args as $key => $value) {
            switch ($key) {
                case 'info':
                case 'name':
                    $rules[$key] = ['required', 'string'];
                    $user->$key = $value;
                    break;
                case 'email':
                    $rules[$key] = ['required', 'email', new AlreadyExists('users', $user->id)];
                    $user->$key = $value;
                    break;
                case 'img_url':
                    $rules[$key] = ['nullable', 'file', 'mimes:jpeg,jpg,png'];
                    break;
                case 'password':
                    $rules[$key] = ['nullable', 'string', 'min:6'];
                    $rules['confirmation'] = ['nullable', 'string', 'same:password'];
                    if (!empty($validation)) {
                        $user->$key = $value;
                    }
                    break;
                case 'role_id':
                    $rules[$key] = ['nullable', 'exists:roles,id'];
                    $user->$key = $value;
                    break;
            }
        }

        $validation = Validator::make($args, $rules);

        if ($validation->fails()) {
            return response($validation->errors()->all(), 422);
        }

        // Check img_url file exists
        if ($request->hasFile('img_url')) {
            try {
                // Attempt to save file
                $user->img_url = FileHelper::saveFile(
                    $request->file('img_url'),
                    'uploads/users/' . $user->id,
                    'user_img'
                );
            } catch (\Exception $e) {
                return response(['errors' => [
                    'img_url' => [$e->getMessage()]
                ]], 400);
            }
        }

        $user->save();

        return response($user, 200);
    }

    /**
     * Remove user
     *
     * @param User $user
     * @return Response
     */
    public function destroy(User $user): Response
    {
        return $this->defaultRemove($user);
    }
}