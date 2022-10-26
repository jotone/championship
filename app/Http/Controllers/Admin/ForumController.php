<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BasicAdminController;
use App\Models\ForumTopic;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ForumController extends BasicAdminController
{
    /**
     * Forum topic list page
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        // Get request parameters
        [$args, $query] = $this->requestData($request);

        return $this->renderPage('admin.forum.index', $request, [
            'routes' => [
                'list'    => route('api.forum.index') . '?' . implode('&', $query),
                'index'   => route('admin.forum.index'),
                'edit'    => route('admin.forum.edit', 0),
                'destroy' => route('api.forum.destroy', 0)
            ],
            'search' => $args['search'] ?? '',
            'title'  => 'Список Форумів'
        ]);
    }

    /**
     * Forum topic creating page
     *
     * @param Request $request
     * @return View
     */
    public function create(Request $request): View
    {
        return $this->renderPage('admin.forum.form', $request, [
            'title' => 'Створення Форуму'
        ]);
    }

    /**
     * Forum topic edit page
     *
     * @param ForumTopic $forum
     * @param Request $request
     * @return View
     */
    public function edit(ForumTopic $forum, Request $request): View
    {
        return $this->renderPage('admin.forum.form', $request, [
            'model' => $forum,
            'title' => 'Редагування Форуму'
        ]);
    }
}