<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\BasicMainController;
use App\Models\ForumTopic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            'jwt'    => Session::has('jwt-token') && in_array(Auth::user()->role->slug, ['superadmin', 'admin'])
                ? Session::get('jwt-token')
                : null,
            'routes' => [
                'list'    => route('api.forum.index') . '?take=0&with[]=author&order[by]=name',
                'edit'    => route('admin.forum.edit', 0),
                'show'    => route('forum.show', 0),
                'update'  => route('api.forum.update', 0),
                'upgrade' => route('api.forum.upgrade')
            ],
            'topics' => ForumTopic::with('messages')->orderBy('pinned', 'desc')->orderBy('position')->get()
        ]);
    }

    /**
     * Forum topic page
     *
     * @param string $forum_url
     * @return View
     */
    public function show(string $forum_url): View
    {
        return $this->renderIndexPage('main.forum.show', [
            'topic' => ForumTopic::with([
                'messages' => function ($q) {
                    return $q->whereNull('parent_id')->with('subComments')->orderBy('pinned', 'desc');
                }
            ])->where('url', $forum_url)->firstOrFail()
        ]);
    }
}