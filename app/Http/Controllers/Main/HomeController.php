<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\BasicMainController;
use App\Models\CustomPage;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends BasicMainController
{
    /**
     * Index page data
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $slug = str_replace('.index', '', $request->route()->getName());

        return $this->renderIndexPage('main.home.index', [
            'page_data' => CustomPage::firstWhere(['slug' => $slug])
        ]);
    }
}