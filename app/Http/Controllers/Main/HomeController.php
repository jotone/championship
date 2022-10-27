<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\BasicMainController;
use App\Models\CustomPage;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends BasicMainController
{
    /**
     * Index page
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        // Get page slug
        $slug = str_replace('.index', '', $request->route()->getName());

        return $this->renderIndexPage('main.home.index', [
            // Get page data by slug
            'page_data' => CustomPage::firstWhere('slug', $slug)
        ]);
    }

    /**
     * Schedule page
     *
     * @return View
     */
    public function schedule(): View
    {
        return $this->renderIndexPage('main.home.schedule', [
            // Get schedule page data
            'page_data' => CustomPage::firstWhere('slug', 'schedule'),
        ]);
    }

    public function groups(): View
    {
        return $this->renderIndexPage('main.home.groups', [
            // Get groups page data
            'page_data' => CustomPage::firstWhere('slug', 'schedule')
        ]);
    }
}