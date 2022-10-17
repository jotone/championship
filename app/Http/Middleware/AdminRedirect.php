<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\{RedirectResponse, Request, Response};

class AdminRedirect
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response|RedirectResponse) $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            if (!empty($user->role)) {
                if ($user->role->slug == 'superadmin') {
                    return $next($request);
                }
                $permission = $user->role->permissions()->firstWhere([
                    'controller' => get_class($request->route()->getController())
                ]);
                if ($permission && in_array($request->route()->getActionMethod(), $permission->allowed_methods)) {
                    return $next($request);
                }
            }
        } else {
            abort(404);
        }

        return response([
            'errors' => [
                'auth' => ['You do not have permissions to perform this action.']
            ]
        ], 403);
    }
}
