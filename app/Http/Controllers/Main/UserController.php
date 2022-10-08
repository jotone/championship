<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\BasicMainController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserController extends BasicMainController
{
    public function show(string $md5_id = null): View
    {
        $view = empty($md5_id) ? 'main.user.profile' : 'main.user.info';

        return $this->renderIndexPage($view, [
            'user' => empty($md5_id) ? Auth::user() : User::whereRaw("md5(id) = '{$md5_id}'")->first()
        ]);
    }
}