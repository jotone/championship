<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BasicApiController;
use App\Http\Requests\{RoleStoreRequest, RoleUpdateRequest};
use App\Models\{Role, Permission};
use Illuminate\Http\{Request, Response};

class RolesController extends BasicApiController
{
    /**
     * Get role list
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        // Get request data
        $args = $this->parseRequest($request);

        // Run query
        $content = Role::query();

        // Set search value
        $search = $args['search'] ?? null;
        // Check search value isset
        if (!empty($search)) {
            $content = $content->where('name', 'like', '%' . $search . '%');
        }

        return $this->apiIndexResponse($content, $args);
    }

    /**
     * Create role
     *
     * @param RoleStoreRequest $request
     * @return Response
     */
    public function store(RoleStoreRequest $request): Response
    {
        // Request data
        $args = $request->validated();
        // Create role
        $role = Role::create($args);
        // Set permissions to role
        if (!empty($args['permissions'])) {
            $this->savePermissions($role->id, $args['permissions']);
        }

        return response($role, 201);
    }

    /**
     * Update role
     *
     * @param Role $role
     * @param RoleUpdateRequest $request
     * @return Response
     */
    public function update(Role $role, RoleUpdateRequest $request): Response
    {
        // Request data
        $args = $request->validated();
        // Modify role
        $role->update($args);
        // Set permissions to role
        if (!empty($args['permissions'])) {
            $this->savePermissions($role->id, $args['permissions']);
        }

        return response($role);
    }

    /**
     * Remove role
     *
     * @param Role $role
     * @return Response
     */
    public function destroy(Role $role): Response
    {
        return $this->defaultRemove($role);
    }

    /**
     * Bind permissions to role
     *
     * @param int $role_id
     * @param $data
     * @return void
     */
    protected function savePermissions(int $role_id, $data): void
    {
        foreach ($data as $controller => $permissions) {
            $permission = Permission::firstWhere([
                'role_id'    => $role_id,
                'controller' => $controller
            ]);

            if (!$permission) {
                Permission::create([
                    'role_id'         => $role_id,
                    'controller'      => $controller,
                    'allowed_methods' => array_keys($permissions)
                ]);
            } else {
                $permission->allowed_methods = array_keys($permissions);
                $permission->save();
            }
        }
    }
}