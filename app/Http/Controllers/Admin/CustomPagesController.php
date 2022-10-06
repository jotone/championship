<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BasicAdminController;
use App\Models\CustomPages;
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

        return $this->renderPage('admin.custompages.index', $request, [
            'routes' => [

            ],
            'search' => $args['search'] ?? '',
            'title'  => 'CustomPages list'
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
        return $this->renderPage('admin.custompages.form', $request, [
            'title' => 'Create CustomPages'
        ]);
    }

    /**
     * CustomPages edit page
     *
     * @param CustomPages $custompages
     * @param Request $request
     * @return View
     */
    public function edit(CustomPages $custompages, Request $request): View
    {
        return $this->renderPage('admin.custompages.form', $request, [
            'model' => $custompages,
            'title' => 'Edit CustomPages'
        ]);
    }
}