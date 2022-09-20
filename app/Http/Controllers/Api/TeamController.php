<?php

namespace App\Http\Controllers\Api;

use App\Classes\FileHelper;
use App\Http\Controllers\BasicApiController;
use App\Http\Requests\TeamRequest;
use App\Models\Team;
use Illuminate\Http\{Request, Response};

class TeamController extends BasicApiController
{
    /**
     * Get team list
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        // Get request data
        $args = $this->parseRequest($request);

        // Run query
        $content = Team::select('teams.*', 'countries.ua as country')
            ->leftJoin('countries', 'teams.country_id', '=', 'countries.id');

        // Set search value
        $search = $args['search'] ?? null;
        // Check search value isset
        if (!empty($search)) {
            $content = $content->where('teams.en', 'like', '%' . $search . '%')
                ->orWhere('teams.ua', 'like', '%' . $search . '%');
        }

        return $this->apiIndexResponse($content, $args);
    }

    /**
     * Create team
     *
     * @param TeamRequest $request
     * @return Response
     */
    public function store(TeamRequest $request): Response
    {
        // Request data
        $args = $request->validated();
        // Create team
        $team = Team::create([
            'ua'         => $args['ua'],
            'en'         => ucfirst($args['en']),
            'country_id' => $args['country_id']
        ]);

        try {
            // Attempt to save file
            if ($request->hasFile('img_url')) {
                $team->img_url = FileHelper::saveFile(
                    $request->file('img_url'),
                    'uploads/teams/' . $team->id,
                    'team_img'
                );
                $team->save();
            }
        } catch (\Exception $e) {
            throw response(['errors' => ['img_url' => [$e->getMessage()]]], 400);
        }

        return response($team, 201);
    }

    /**
     * Update team
     *
     * @param Team $team
     * @param TeamRequest $request
     * @return Response
     */
    public function update(Team $team, TeamRequest $request): Response
    {
        // Request data
        $args = $request->validated();
        // Modify team
        $team->en = ucfirst($args['en']);
        $team->ua = $args['ua'];
        $team->country_id = $args['country_id'];
        // Save image
        try {
            // Attempt to save file
            if ($request->hasFile('img_url')) {
                $team->img_url = FileHelper::saveFile(
                    $request->file('img_url'),
                    'uploads/teams/' . $team->id,
                    'team_img'
                );
            }
        } catch (\Exception $e) {
            throw response(['errors' => ['img_url' => [$e->getMessage()]]], 400);
        }

        $team->save();

        return response($team);
    }

    /**
     * Remove team
     *
     * @param Team $team
     * @return Response
     */
    public function destroy(Team $team): Response
    {
        return $this->defaultRemove($team);
    }
}
