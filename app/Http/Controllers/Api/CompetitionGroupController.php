<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BasicApiController;
use App\Http\Requests\CompetitionGroupRequest;
use App\Models\CompetitionGroup;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\{Request, Response};

class CompetitionGroupController extends BasicApiController
{
    /**
     * Get competition groups list
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        return $this->renderIndexPage($request, CompetitionGroup::class);
    }

    /**
     * Create competition group
     *
     * @param CompetitionGroupRequest $request
     * @return Response
     */
    public function store(CompetitionGroupRequest $request): Response
    {
        $args = $request->validated();

        if (empty($args['position'])) {
            $args['position'] = CompetitionGroup::where('competition_id', $args['competition_id'])->count();
        }

        $group = CompetitionGroup::create([
            'name'           => empty($args['name']) ? 'New group ' . $args['position'] : $args['name'],
            'competition_id' => $args['competition_id'],
            'position'       => $args['position'],
            'stage'          => $args['stage'] ?? 0
        ]);

        return response($group, 201);
    }

    /**
     * Update competition group
     *
     * @param CompetitionGroup $competition_group
     * @param Request $request
     * @return Response
     */
    public function update(CompetitionGroup $competition_group, Request $request): Response
    {
        $args = $request->only(['name', 'position']);

        $rules = [];
        foreach ($args as $key => $val) {
            switch ($key) {
                case 'name':
                    $rules[$key] = ['required', 'string'];
                    $competition_group->$key = $val;
                    break;
                case 'position':
                    $rules[$key] = ['required', 'numeric'];
                    $competition_group->$key = $val;
                    break;
            }
        }

        if (!empty($rules)) {
            $validator = Validator::make($args, $rules);

            if ($validator->fails()) {
                return response($validator->errors()->all(), 400);
            }

            $competition_group->save();
        }

        return response($competition_group);
    }

    /**
     * Modify group positions
     *
     * @param Request $request
     * @return Response
     */
    public function upgrade(Request $request): Response
    {
        $args = $request->only(['positions', 'stages']);

        $validator = Validator::make($args, [
            'positions'   => ['nullable', 'array'],
            'positions.*' => ['exists:competition_groups,id'],
            'stages'      => ['nullable', 'array'],
            'stages.*'    => ['numeric']
        ]);

        if ($validator->fails()) {
            return response($validator->errors()->all(), 400);
        }

        if (!empty($args['positions'])) {
            foreach ($args['positions'] as $pos => $id) {
                $group = CompetitionGroup::findOrFail($id);
                $group->position = $pos;
                $group->save();
            }
        }

        if (!empty($args['stages'])) {
            foreach ($args['stages'] as $stage => $id) {
                $group = CompetitionGroup::findOrFail($id);
                $group->stage = $stage + 1;
                $group->save();
            }
        }

        return response([]);
    }

    /**
     * Remove group
     *
     * @param CompetitionGroup $competition_group
     * @return Response
     * @throws \Exception
     */
    public function destroy(CompetitionGroup $competition_group): Response
    {
        $competition_group->competition->decrement('groups_number');
        $competition_group->delete();

        return response([], 204);
    }
}