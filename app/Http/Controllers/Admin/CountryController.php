<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BasicAdminController;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CountryController extends BasicAdminController
{
    /**
     * Country list page
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        // Get request parameters
        [$args, $query] = $this->requestData($request);

        return $this->renderPage('admin.countries.index', $request, [
            'routes' => [
                'list'    => route('api.countries.index') . '?' . implode('&', $query),
                'index'   => route('admin.countries.index'),
                'edit'    => route('admin.countries.edit', 0),
                'destroy' => route('api.countries.destroy', 0)
            ],
            'search' => $args['search'] ?? '',
            'title'  => 'Country list'
        ]);
    }

    /**
     * Country creating page
     *
     * @param Request $request
     * @return View
     */
    public function create(Request $request): View
    {
        return $this->renderPage('admin.countries.form', $request, [
            'title' => 'Create Country'
        ]);
    }

    /**
     * Country edit page
     *
     * @param Country $country
     * @param Request $request
     * @return View
     */
    public function edit(Country $country, Request $request): View
    {
        return $this->renderPage('admin.countries.form', $request, [
            'model' => $country,
            'title' => 'Edit Country'
        ]);
    }
}