<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BasicAdminController;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends BasicAdminController
{
    public function index(Request $request): View
    {
        return $this->renderPage('admin.dashboard.index', $request, [
            'title'  => 'Адмін панель'
        ]);
    }
}