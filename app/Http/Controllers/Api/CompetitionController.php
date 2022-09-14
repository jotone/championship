<?php

namespace App\Http\Controllers\Api;

use App\Classes\FileHelper;
use App\Http\Controllers\BasicApiController;
use App\Http\Requests\{CompetitionStoreRequest, CompetitionUpdateRequest};
use App\Models\{Competition, CompetitionGroup};
use Illuminate\Http\{Request, Response};

class CompetitionController extends BasicApiController
{
    protected $start_char_number = 65; // Code of "A" symbol

    /**
     * Get competition list
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        // Get request data
        $args = $this->parseRequest($request);

        // Run query
        $content = Competition::query();

        // Set search value
        $search = $args['search'] ?? null;
        // Check search value isset
        if (!empty($search)) {
            $content = $content->where('name', 'like', '%' . $search . '%');
        }

        return $this->apiIndexResponse($content, $args);
    }

    /**
     * Create competition
     *
     * @param CompetitionStoreRequest $request
     * @return Response
     */
    public function store(CompetitionStoreRequest $request): Response
    {
        // Request data
        $args = $request->validated();
        // Create competition
        $competition = Competition::create([
            'name'          => $args['name'],
            'slug'          => $args['slug'],
            'groups_number' => $args['groups_number'],
            'img_url'       => null,
            'bg_color'      => $args['bg_color'][0] != '#' ? '#' . $args['bg_color'] : $args['bg_color'],
            'text_color'    => $args['text_color'][0] != '#' ? '#' . $args['text_color'] : $args['text_color'],
            'start_at'      => $args['start_at'],
            'finish_at'     => $args['finish_at'],
        ]);

        //Create competition groups
        $n = $this->start_char_number + $args['groups_number'];
        for ($i = $this->start_char_number; $i < $n; $i++) {
            CompetitionGroup::create([
                'name'           => chr($i),
                'competition_id' => $competition->id
            ]);
        }

        try {
            // Attempt to save file
            if ($request->hasFile('img_url')) {
                $competition->img_url = FileHelper::saveFile(
                    $request->file('img_url'),
                    'uploads/competition/' . $competition->id,
                    'competition_img'
                );
                $competition->save();
            }
        } catch (\Exception $e) {
            throw response(['errors' => ['img_url' => [$e->getMessage()]]], 400);
        }

        return response($competition, 201);
    }

    /**
     * Update competition
     *
     * @param Competition $competition
     * @param CompetitionUpdateRequest $request
     * @return Response
     */
    public function update(Competition $competition, CompetitionUpdateRequest $request): Response
    {
        // Request data
        $args = $request->validated();
        // Modify model
        $competition->name = $args['name'];
        $competition->slug = $args['slug'];
        $competition->groups_number = $args['groups_number'];
        $competition->bg_color = $args['bg_color'][0] != '#' ? '#' . $args['bg_color'] : $args['bg_color'];
        $competition->text_color = $args['text_color'][0] != '#' ? '#' . $args['text_color'] : $args['text_color'];
        $competition->start_at = $args['start_at'];
        $competition->finish_at = $args['finish_at'];

        try {
            // Attempt to save file
            if ($request->hasFile('img_url')) {
                $competition->img_url = FileHelper::saveFile(
                    $request->file('img_url'),
                    'uploads/competition/' . $competition->id,
                    'competition_img'
                );
                $competition->save();
            }
        } catch (\Exception $e) {
            throw response(['errors' => ['img_url' => [$e->getMessage()]]], 400);
        }

        $competition->save();

        return response($competition);
    }

    /**
     * Remove competition
     *
     * @param Competition $competition
     * @return Response
     */
    public function destroy(Competition $competition): Response
    {
        return $this->defaultRemove($competition);
    }
}
