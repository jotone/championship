<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\BasicMainController;
use App\Models\ForumTopic;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class ForumController extends BasicMainController
{
    /**
     * Forum main page
     *
     * @return View
     */
    public function index(): View
    {
        return $this->renderIndexPage('main.forum.index', [
            'jwt'    => Session::has('jwt-token') ? Session::get('jwt-token') : null,
            'routes' => [
                'list'    => route('api.forum.index') . '?take=0&with[]=author&order[by]=name',
                'edit'    => route('admin.forum.edit', 0),
                'update'  => route('api.forum.update', 0),
                'upgrade' => route('api.forum.upgrade')
            ],
            'topics' => ForumTopic::with('messages')->orderBy('pinned', 'desc')->orderBy('position')->get()
        ]);
    }

    public function show(ForumTopic $forum)
    {

    }
}