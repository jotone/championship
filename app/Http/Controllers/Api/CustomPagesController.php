<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BasicApiController;
use App\Http\Requests\{CustomPagesStoreRequest, CustomPagesUpdateRequest};
use App\Models\CustomPages;
use Illuminate\Http\{Request, Response};

class CustomPagesController extends BasicApiController
{
    /**
     * Get custompages list
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        // Get request data
        $args = $this->parseRequest($request);

        // Run query
        $content = CustomPages::query();

        // Set search value
        $search = $args['search'] ?? null;
        // Check search value isset
        if (!empty($search)) {
            $content = $content->where('name', 'like', '%' . $search . '%');
        }

        return $this->apiIndexResponse($content, $args);
    }

    /**
     * Create custompages
     *
     * @param CustomPagesStoreRequest $request
     * @return Response
     */
    public function store(CustomPagesStoreRequest $request): Response
    {
        // Request data
        $args = $request->validated();
        // Create custompages
        $custompages = CustomPages::create($args);

        return response($custompages, 201);
    }

    /**
     * Update custompages
     *
     * @param CustomPages $custompages
     * @param CustomPagesUpdateRequest $request
     * @return Response
     */
    public function update(CustomPages $custompages, CustomPagesUpdateRequest $request): Response
    {
        // Request data
        $args = $request->validated();
        // Modify model

        $custompages->save();

        return response($custompages);
    }

    /**
     * Remove custompages
     *
     * @param CustomPages $custompages
     * @return Response
     */
    public function destroy(CustomPages $custompages): Response
    {
        return $this->defaultRemove($custompages);
    }
}
