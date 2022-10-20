<?php

namespace App\Http\Controllers;

use App\Models\{Competition, Settings, UserForm};
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

        $setup = Settings::whereIn('key', ['site_title', 'registration_enable'])->get()->keyBy('key');
        $site_title = !empty($setup['site_title']->value) ? $setup['site_title']->value : '';
        $meta_title = (array_diff(
            isset($share['page_data'])
                ? [$site_title, $share['page_data']->meta_title]
                : [$site_title],
            ['', null]));

        $setup->put('meta_title', implode(' | ', $meta_title));

        return view($view, array_merge([
            'competition' => Competition::with(['groups' => fn($q) => $q->with('games')])->find(1),
            'messages'    => $messages,
            'results'     => UserForm::with(['bets', 'user'])
                ->where('competition_id', 1)
                ->orderBy('points', 'desc')
                ->get(),
            'setup'       => $setup
        ], $share));
    }
}