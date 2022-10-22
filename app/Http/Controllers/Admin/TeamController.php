<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BasicAdminController;
use App\Models\Country;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeamController extends BasicAdminController
{
    /**
     * Team list page
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        // Get request parameters
        [$args, $query] = $this->requestData($request);

        return $this->renderPage('admin.teams.index', $request, [
            'routes' => [
                'list'    => route('api.teams.index') . '?' . implode('&', $query),
                'index'   => route('admin.countries.teams.index'),
                'edit'    => route('admin.countries.teams.edit', 0),
                'destroy' => route('api.teams.destroy', 0),
                'country' => route('admin.countries.edit', 0)
            ],
            'search' => $args['search'] ?? '',
            'title'  => 'Team list'
        ]);
    }

    /**
     * Team creating page
     *
     * @param Request $request
     * @return View
     */
    public function create(Request $request): View
    {
        return $this->renderPage('admin.teams.form', $request, [
            'countries' => Country::orderBy('ua')->get(),
            'title'     => 'Create Team'
        ]);
    }

    /**
     * Team edit page
     *
     * @param Team $team
     * @param Request $request
     * @return View
     */
    public function edit(Team $team, Request $request): View
    {
        return $this->renderPage('admin.teams.form', $request, [
            'countries' => Country::orderBy('ua')->get(),
            'model'     => $team,
            'title'     => 'Edit Team'
        ]);
    }
}