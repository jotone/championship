<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BasicApiController;
use App\Http\Requests\{CustomPagesStoreRequest, CustomPagesUpdateRequest};
use App\Models\CustomPage;
use Illuminate\Http\{Request, Response};

class CustomPagesController extends BasicApiController
{
    /**
     * Get custom pages list
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        // Get request data
        $args = $this->parseRequest($request);

        // Run query
        $content = CustomPage::query();

        // Set search value
        $search = $args['search'] ?? null;
        // Check search value isset
        if (!empty($search)) {
            $content = $content->where('name', 'like', '%' . $search . '%');
        }

        return $this->apiIndexResponse($content, $args);
    }

    /**
     * Create custom pages
     *
     * @param CustomPagesStoreRequest $request
     * @return Response
     */
    public function store(CustomPagesStoreRequest $request): Response
    {
        // Request data
        $args = $request->validated();

        dd($args);
        // Create custompages
        $custompages = CustomPage::create($args);

        return response($custompages, 201);
    }

    /**
     * Update custompages
     *
     * @param CustomPage $custompages
     * @param CustomPagesUpdateRequest $request
     * @return Response
     */
    public function update(CustomPage $custompages, CustomPagesUpdateRequest $request): Response
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
     * @param CustomPage $custompages
     * @return Response
     */
    public function destroy(CustomPage $custompages): Response
    {
        return $this->defaultRemove($custompages);
    }
}
