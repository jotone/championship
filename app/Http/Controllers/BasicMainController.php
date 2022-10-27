<?php

namespace App\Http\Controllers;

use App\Models\{Competition, Settings, UserForm};
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class BasicMainController extends Controller
{
    /**
     * @var Competition
     */
    protected $competition;

    public function __construct()
    {
        $this->competition = Competition::with([
            'groups' => fn($q) => $q->with([
                'games' => fn ($inner_query) => $inner_query->orderBy('start_at')
            ])->orderBy('stage')->orderBy('position'),
            'teams' => fn ($q) => $q->orderBy('score')
        ])->firstWhere('slug', 'world-cup-2022');
    }

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

        // Get default settings list
        $setup = Settings::whereIn('key', ['site_title', 'registration_enable'])->get()->keyBy('key');
        // Get site title
        $site_title = !empty($setup['site_title']->value) ? $setup['site_title']->value : '';
        // Concat site title and meta title
        $meta_title = (array_diff(
            isset($share['page_data'])
                ? [$site_title, $share['page_data']->meta_title]
                : [$site_title],
            ['', null]));
        // Set meta title
        $setup->put('meta_title', implode(' | ', $meta_title));

        return view($view, array_merge([
            'competition' => $this->competition,
            'messages'    => $messages,
            'results'     => UserForm::with(['bets', 'user'])
                ->where('competition_id', 1)
                ->orderBy('points', 'desc')
                ->get(),
            'setup'       => $setup,
            // Competition team list
            'teams'     => $this->teamList()
        ], $share));
    }

    /**
     * Get competition team ID list
     * @return array
     */
    protected function teamIDs(): array
    {
        return !empty($this->competition) ? $this->competition->teams->pluck('entity_id')->toArray() : [];
    }

    /**
     * Get competition team list
     *
     * @param bool $keyBy
     * @return Collection
     */
    protected function teamList(bool $keyBy = true): Collection
    {
        $teamIDs = $this->teamIDs();
        $list = !empty($teamIDs)
            ? $this->competition->teams[0]->entity::whereIn('id', $teamIDs)->orderBy('ua')->get()
            : collect([]);

        return $keyBy ? $list->keyBy('id') : $list;
    }
}