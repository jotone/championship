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
     * Get role list
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
                ->orWhere('ua', 'like', '%' . $search . '%')
                ->orWhere('ru', 'like', '%' . $search . '%');
        }

        return $this->apiIndexResponse($content, $args);
    }

    /**
     * Create role
     *
     * @param CountryRequest $request
     * @return Response
     */
    public function store(CountryRequest $request): Response
    {
        // Request data
        $args = $request->validated();
        // Create role
        $country = Country::create([
            'code' => $args['code'],
            'en'   => $args['en'],
            'ua'   => $args['ua'],
            'ru'   => $args['ru']
        ]);

        if ($request->hasFile('img_url')) {
            try {
                // Attempt to save file
                $country->img_url = FileHelper::saveFile($request->file('img_url'), 'images/country/');
                $country->save();
            } catch (\Exception $e) {
                return response(['errors' => ['img_url' => [$e->getMessage()]]], 400);
            }
        }

        return response($country, 201);
    }

    /**
     * Update role
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
        $country->ru = $args['ru'];
        // Set image
        // Check img_url file exists
        if ($request->hasFile('img_url')) {
            try {
                // Attempt to save file
                $country->img_url = FileHelper::saveFile($request->file('img_url'), 'images/country/' . $args['code'] . '.svg');
            } catch (\Exception $e) {
                return response(['errors' => [
                    'img_url' => [$e->getMessage()]
                ]], 400);
            }
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
