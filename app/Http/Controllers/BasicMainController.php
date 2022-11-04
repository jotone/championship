<?php

namespace App\Http\Controllers;

use App\Traits\SetupVariablesTrait;
use App\Models\{Competition, UserForm};
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class BasicMainController extends Controller
{
    use SetupVariablesTrait;

    /**
     * @var Competition
     */
    protected $competition;

    public function __construct()
    {
        $this->competition = Competition::with([
            'groups' => fn($q) => $q->with([
                'games' => fn($inner_query) => $inner_query->orderBy('start_at')
            ])->orderBy('stage')->orderBy('position'),
            'teams'  => fn($q) => $q->orderBy('score')
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

        return view($view, array_merge([
            'competition' => $this->competition,
            'messages'    => $messages,
            'results'     => UserForm::with(['bets', 'user'])
                ->where('competition_id', $this->competition->id)
                ->orderBy('points', 'desc')
                ->get(),
            'setup'       => $this->settingsData(),
            // Competition team list
            'teams'       => $this->teamList()
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