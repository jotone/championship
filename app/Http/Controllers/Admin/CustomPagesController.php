<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BasicAdminController;
use App\Models\CustomPage;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomPagesController extends BasicAdminController
{
    /**
     * CustomPages list page
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        // Get request parameters
        [$args, $query] = $this->requestData($request);

        return $this->renderPage('admin.custom-pages.index', $request, [
            'routes' => [
                'list'    => route('api.pages.index') . '?' . implode('&', $query),
                'index'   => route('admin.pages.index'),
                'edit'    => route('admin.pages.edit', 0),
                'destroy' => route('api.pages.destroy', 0)
            ],
            'search' => $args['search'] ?? '',
            'title'  => 'Pages list'
        ]);
    }

    /**
     * CustomPages creating page
     *
     * @param Request $request
     * @return View
     */
    public function create(Request $request): View
    {
        return $this->renderPage('admin.custom-pages.form', $request, [
            'title' => 'Create Page'
        ]);
    }

    /**
     * CustomPages edit page
     *
     * @param CustomPage $custompages
     * @param Request $request
     * @return View
     */
    public function edit(CustomPage $custompages, Request $request): View
    {
        return $this->renderPage('admin.custom-pages.form', $request, [
            'model' => $custompages,
            'title' => 'Edit Page'
        ]);
    }
}