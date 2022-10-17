<?php

namespace App\Http\Controllers\Main;

use App\Classes\FileHelper;
use App\Http\Controllers\BasicMainController;
use App\Models\Competition;
use App\Models\User;
use Illuminate\Http\{Response, Request};
use Illuminate\Support\Facades\{Auth, Hash, Validator};
use Illuminate\View\View;

class UserController extends BasicMainController
{
    /**
     * View user profile
     *
     * @param string|null $md5_id
     * @return View
     */
    public function show(string $md5_id = null): View
    {
        $view = empty($md5_id) ? 'main.user.profile' : 'main.user.info';

        $user = empty($md5_id) ? Auth::user() : User::whereRaw("md5(id) = '{$md5_id}'")->first();

        abort_if(!$user, 404);

        return $this->renderIndexPage($view, ['user' => $user]);
    }

    /**
     * @param string $md5_id
     *
     * @return View
     */
    public function results(string $md5_id): View
    {
        $user = User::with([
            'forms' => function ($q) {
                return $q->with('bets')->where('competition_id', 1);
            }
        ])->whereRaw("md5(id) = '{$md5_id}'")->firstOrFail();

        $bets = [];
        foreach ($user->forms[0]->bets as $bet) {
            if (!empty($bet->game_id) && $bet->game) {
                $bets['group'][$bet->group_id][$bet->game_id] = $bet->scores;
            } else {
                $bets['playOff'][$bet->group_id] = $bet->scores;
            }
        }

        $competition = Competition::with('teams')->findOrFail(1);

        $teamIDs = $competition->teams->pluck('entity_id')->toArray();

        $teams = !empty($teamIDs)
            ? $competition->teams[0]->entity::whereIn('id', $teamIDs)->orderBy('ua')->get()
            : [];

        return $this->renderIndexPage('main.user.results', [
            'bets'  => $bets,
            'user'  => $user,
            'teams' => $teams->keyBy('id')
        ]);
    }

    /**
     * Update user data
     *
     * @param Request $request
     * @return Response
     */
    public function update(Request $request): Response
    {
        // Get current user
        $user = Auth::user();
        // Return 404 status if user session does not exist
        abort_if(!$user, 404);
        // Request data
        $args = $request->only(['img_url', 'old_pwd', 'password', 'confirmation', 'info']);

        $rules = [
            'img_url' => ['nullable', 'file', 'mimes:jpeg,jpg,png'],
            'info'    => ['nullable', 'string']
        ];

        // Check old password
        if (!empty($args['old_pwd']) && !empty($args['password'])) {
            if (!Hash::check($args['old_pwd'], $user->password)) {
                return response(['errors' => ['Невірно введено старий пароль']], 422);
            }

            $rules['password'] = ['required', 'string', 'min:6'];
            $rules['confirmation'] = ['required', 'string', 'same:password'];

            $user->password = $args['password'];
        }
        // Set info value
        $user->info = $args['info'];
        // Run validation
        $validation = Validator::make($args, $rules);
        if ($validation->fails()) {
            return response(['errors' => $validation->messages()], 422);
        }

        // Save image
        if (!empty($args['img_url'])) {
            try {
                // Attempt to save file
                $user->img_url = FileHelper::saveFile($args['img_url'], 'uploads/users/' . $user->id, 'user_img');
            } catch (\Exception $e) {
                response(['errors' => [$e->getMessage()]], 500);
            }
        }

        $user->save();

        return response([
            'messages' => [
                'success' => ['Ваш профіль було збережено']
            ]
        ]);
    }
}