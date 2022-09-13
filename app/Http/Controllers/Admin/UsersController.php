<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BasicAdminController;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UsersController extends BasicAdminController
{
    /**
     * User list page
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        // Get request parameters
        [$args, $query] = $this->requestData($request);

        return $this->renderPage('admin.users.index', $request, [
            'routes' => [
                'list'    => route('api.users.index') . '?with=role&' . implode('&', $query),
                'index'   => route('admin.users.index'),
                'edit'    => route('admin.users.edit', 0),
                'destroy' => route('api.users.destroy', 0),
                'role'    => route('admin.users.roles.edit', 0)
            ],
            'search' => $args['search'] ?? '',
            'title'  => 'User list'
        ]);
    }

    /**
     * User create page
     *
     * @param Request $request
     * @return View
     */
    public function create(Request $request): View
    {
        return $this->renderPage('admin.users.form', $request, [
            'roles' => Role::where('level', '>=', 0)->orderBy('name')->get(),
            'title' => 'Create User'
        ]);
    }

    /**
     * User edit page
     *
     * @param User $user
     * @param Request $request
     * @return View
     */
    public function edit(User $user, Request $request): View
    {
        return $this->renderPage('admin.users.form', $request, [
            'model' => $user,
            'roles' => Role::where('level', '>=', 0)->orderBy('name')->get(),
            'title' => 'Create User'
        ]);
    }
}