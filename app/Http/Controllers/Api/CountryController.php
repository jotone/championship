<?php

namespace App\Http\Controllers\Api;

use App\Classes\FileHelper;
use App\Http\Controllers\BasicApiController;
use App\Http\Requests\CountryRequest;
use App\Models\Country;
use Illuminate\Http\{Request, Response};

class CountryController extends BasicApiController
{
    /**
     * Get country list
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        // Get request data
        $args = $this->parseRequest($request);

        // Run query
        $content = Country::query();

        // Set search value
        $search = $args['search'] ?? null;
        // Check search value isset
        if (!empty($search)) {
            $content = $content->where('en', 'like', '%' . $search . '%')
                ->orWhere('ua', 'like', '%' . $search . '%');
        }

        return $this->apiIndexResponse($content, $args);
    }

    /**
     * Create country
     *
     * @param CountryRequest $request
     * @return Response
     */
    public function store(CountryRequest $request): Response
    {
        // Request data
        $args = $request->validated();
        // Create country
        $country = Country::create([
            'code' => $args['code'],
            'ua'   => $args['ua'],
            'en'   => $args['en']
        ]);

        try {
            // Attempt to save file
            if ($request->hasFile('img_url')) {
                $country->img_url = FileHelper::saveFile($request->file('img_url'), 'images/country/');
                $country->save();
            }
        } catch (\Exception $e) {
            throw response(['errors' => ['img_url' => [$e->getMessage()]]], 400);
        }

        return response($country, 201);
    }

    /**
     * Update country
     *
     * @param Country $country
     * @param CountryRequest $request
     * @return Response
     */
    public function update(Country $country, CountryRequest $request): Response
    {
        // Request data
        $args = $request->validated();
        // Modify country
        $country->code = $args['code'];
        $country->en = $args['en'];
        $country->ua = $args['ua'];
        // Save image
        try {
            // Attempt to save file
            if ($request->hasFile('img_url')) {
                $country->img_url = FileHelper::saveFile($request->file('img_url'), 'images/country/');
            }
        } catch (\Exception $e) {
            throw response(['errors' => ['img_url' => [$e->getMessage()]]], 400);
        }

        $country->save();

        return response($country);
    }

    /**
     * Remove country
     *
     * @param Country $country
     * @return Response
     */
    public function destroy(Country $country): Response
    {
        return $this->defaultRemove($country);
    }
}
