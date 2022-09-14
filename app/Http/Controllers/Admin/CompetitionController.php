<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BasicAdminController;
use App\Models\Competition;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompetitionController extends BasicAdminController
{
    /**
     * Competition list page
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        // Get request parameters
        [$args, $query] = $this->requestData($request);

        return $this->renderPage('admin.competitions.index', $request, [
            'routes' => [
                'list'    => route('api.competitions.index') . '?' . implode('&', $query),
                'index'   => route('admin.competitions.index'),
                'edit'    => route('admin.competitions.edit', 0),
                'destroy' => route('api.competitions.destroy', 0)
            ],
            'search' => $args['search'] ?? '',
            'title'  => 'Competition list'
        ]);
    }

    /**
     * Competition creating page
     *
     * @param Request $request
     * @return View
     */
    public function create(Request $request): View
    {
        return $this->renderPage('admin.competitions.form', $request, [
            'title' => 'Create Competition'
        ]);
    }

    /**
     * Competition edit page
     *
     * @param Competition $competition
     * @param Request $request
     * @return View
     */
    public function edit(Competition $competition, Request $request): View
    {
        return $this->renderPage('admin.competitions.form', $request, [
            'model' => $competition,
            'title' => 'Edit Competition'
        ]);
    }
}