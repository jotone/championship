<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BasicApiController;
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
        // Get request data
        $args = $this->parseRequest($request);

        // Run query
        $content = CompetitionGroup::query();

        // Set search value
        $search = $args['search'] ?? null;
        // Check search value isset
        if (!empty($search)) {
            $content = $content->where('name', 'like', '%' . $search . '%');
        }

        return $this->apiIndexResponse($content, $args);
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

        $validator = Validator::make($args, $rules);

        if ($validator->fails()) {
            return response($validator->errors()->all(), 400);
        }

        $competition_group->save();

        return response($competition_group);
    }
}