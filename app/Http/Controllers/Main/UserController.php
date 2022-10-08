<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\BasicMainController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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

        return $this->renderIndexPage($view, [
            'user' => empty($md5_id) ? Auth::user() : User::whereRaw("md5(id) = '{$md5_id}'")->firstOrFail()
        ]);
    }

    /**
     * @param string $md5_id
     *
     * @return View
     */
    public function results(string $md5_id): View
    {
        return $this->renderIndexPage('main.user.results', [
            'user' => User::whereRaw("md5(id) = '{$md5_id}'")->firstOrFail()
        ]);
    }
}