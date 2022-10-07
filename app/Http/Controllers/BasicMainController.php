<?php

namespace App\Http\Controllers;

use App\Models\UserForm;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class BasicMainController extends Controller
{
    /**
     * Default page render
     *
     * @param string $view
     * @param array $share
     * @return View
     */
    protected function renderIndexPage(string $view, array $share = []): View
    {
        // Get session messages
        $messages = Session::has('messages') ? Session::get('messages') : [];
        Session::remove('messages');

        return view($view, array_merge([
            'messages' => $messages,
            'results'  => UserForm::with(['bets', 'user'])->where('competition_id', 1)->orderBy('points')->get()
        ], $share));
    }
}