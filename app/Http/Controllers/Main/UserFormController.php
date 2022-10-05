<?php

namespace App\Http\Controllers\Main;

use App\Models\Competition;
use Illuminate\View\View;

class UserFormController
{
    /**
     * View User Form page
     *
     * @return View
     */
    public function index(): View
    {
        $competition = Competition::with([
            'groups' => function ($q) {
                return $q->orderBy('stage')->orderBy('position');
            }
        ])->where('slug', 'world-cup-2022')->first();

        return view('main.user-form.index', [
            'competition' => $competition,
            'teams'       => $competition->teams
        ]);
    }
}