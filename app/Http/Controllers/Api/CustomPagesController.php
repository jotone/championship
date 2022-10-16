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
        return response(CustomPage::create($request->validated()), 201);
    }

    /**
     * Update custom page
     *
     * @param CustomPage $page
     * @param CustomPagesUpdateRequest $request
     * @return Response
     */
    public function update(CustomPage $page, CustomPagesUpdateRequest $request): Response
    {
        // Request data
        $args = $request->validated();

        // Modify model
        $page->update($args);

        return response($page);
    }

    /**
     * Remove custom pages
     *
     * @param CustomPage $page
     * @return Response
     */
    public function destroy(CustomPage $page): Response
    {
        return $this->defaultRemove($page);
    }
}
