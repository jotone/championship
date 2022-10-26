<?php

namespace App\Http\Controllers;

use App\Models\AdminMenu;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Session};
use Illuminate\View\View;

class BasicAdminController extends Controller
{
    /**
     * Default page render
     *
     * @param string $view
     * @param Request $request
     * @param array $share
     * @return View
     */
    public function renderPage(string $view, Request $request, array $share = []): View
    {
        $user = Auth::user();
        return view($view, array_merge([
            'breadcrumbs' => $this->breadcrumbs($request),
            'menu'        => AdminMenu::whereNull('parent_id')->with('subMenus')->orderBy('position')->get(),
            'jwt_token'   => Session::get('jwt-token'),
            'setup'       => Settings::whereIn('key', ['registration_enable', 'site_title'])->get()->keyBy('key'),
            'user'        => $user
        ], $share));
    }

    /**
     * Build breadcrumbs array
     *
     * @param Request $request
     * @return array
     */
    protected function breadcrumbs(Request $request): array
    {
        $path = explode('.', $request->route()->getName());

        $breadcrumbs = [];
        $temp = '';
        for ($i = 0; $i < count($path); $i++) {
            $temp .= $path[$i] . '.';
            $route = $temp . 'index';
            if ($i == count($path) - 1) {
                switch ($path[$i]) {
                    case 'index':
                        break;
                    case 'create':
                        $breadcrumbs[] = ['name' => 'Create'];
                        break;
                    case 'edit':
                        $breadcrumbs[] = ['name' => 'Edit'];
                        break;
                }
            } else {
                $breadcrumbs[] = [
                    'url'  => route($route),
                    'name' => AdminMenu::where('url', $route)->value('name')
                ];
            }
        }

        return $breadcrumbs;
    }

    /**
     * Get request data
     *
     * @param Request $request
     * @return array
     */
    protected function requestData(Request $request): array
    {
        // Request data
        $args = $request->only(['page', 'search']);
        // Current page number
        $query = [
            'page=' . (isset($args['page']) && is_numeric($args['page']) && $args['page'] > 0 ? $args['page'] : 1)
        ];
        // Check search request
        if (!empty($args['search']) && strlen($args['search']) > 2) {
            $query[] = 'search=' . $args['search'];
        }

        return [$args, $query];
    }
}