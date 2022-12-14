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
            'title'  => 'Список Чемпіонатів'
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
            'tab'   => 'competition',
            'title' => 'Створення Чемпіонату'
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
            'model'  => $competition,
            'tab'    => $request->has('tab') ? $request->get('tab') : 'competition',
            'title'  => 'Редагування Чемпіонату',
            'routes' => [
                'competition' => [
                    'list' => route('api.competitions.index') . '?with[]=groups'
                ],
                'country' => [
                    'list' => route('api.countries.index') . '?take=0'
                ],
                'game'    => [
                    'update'  => route('api.competition-group-games.update', 0),
                    'destroy' => route('api.competition-group-games.destroy', 0),
                    'delete'  => route('api.competition-group-games.delete', [0,0])
                ],
                'group'   => [
                    'list'    => route('api.competition-groups.index') . '?take=0&with[]=games&with[]=teams&order[by]=position&where[competition_id]=' . $competition->id,
                    'store'   => route('api.competition-groups.store'),
                    'update'  => route('api.competition-groups.update', 0),
                    'upgrade' => route('api.competition-groups.upgrade'),
                    'destroy' => route('api.competition-groups.destroy', 0)
                ],
                'team'    => [
                    'list'    => route('api.teams.index') . '?take=0',
                    'destroy' => route('api.competition-group-teams.destroy', 0)
                ]
            ]
        ]);
    }
}