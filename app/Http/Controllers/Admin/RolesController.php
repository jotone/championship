<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BasicAdminController;
use App\Models\Role;
use App\Traits\PermissionsTrait;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RolesController extends BasicAdminController
{
    use PermissionsTrait;

    /**
     * Role list page
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        // Get request parameters
        [$args, $query] = $this->requestData($request);

        return $this->renderPage('admin.roles.index', $request, [
            'routes' => [
                'list'    => route('api.roles.index') . '?' . implode('&', $query),
                'index'   => route('admin.users.roles.index'),
                'edit'    => route('admin.users.roles.edit', 0),
                'destroy' => route('api.roles.destroy', 0)
            ],
            'search' => $args['search'] ?? '',
            'title'  => 'Role list'
        ]);
    }

    /**
     * Role creating page
     *
     * @param Request $request
     * @return View
     */
    public function create(Request $request): View
    {
        return $this->renderPage('admin.roles.form', $request, [
            'permissions' => $this->permissionList(['app/Http/Controllers/Admin', 'app/Http/Controllers/Api']),
            'title'       => 'Create Role'
        ]);
    }

    /**
     * Role edit page
     *
     * @param Role $role
     * @param Request $request
     * @return View
     */
    public function edit(Role $role, Request $request): View
    {
        return $this->renderPage('admin.roles.form', $request, [
            'model'             => $role,
            'model_permissions' => $role->permissions()->get()->keyBy('controller')->toArray(),
            'permissions'       => $this->permissionList(['app/Http/Controllers/Admin', 'app/Http/Controllers/Api']),
            'title'             => 'Edit Role'
        ]);
    }
}
