<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\BasicMainController;
use Illuminate\View\View;

class ForumController extends BasicMainController
{
    public function index(): View
    {
        return $this->renderIndexPage('main.forum.index');
    }
}