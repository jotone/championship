<?php

namespace App\Traits;

use App\Models\Settings;

trait SetupVariablesTrait
{
    /**
     * Get default settings for abstract page
     * @param array $sections
     * @return mixed
     */
    protected function settingsData(array $sections = ['common', 'pages'])
    {
        // Get default settings list
        $setup = Settings::whereIn('section', $sections)->get()->keyBy('key');
        if (empty($setup['fav_icon']->value) || !file_exists(public_path($setup['fav_icon']->value))) {
            unset($setup['fav_icon']);
        }

        if (empty($setup['logo_img_url']->value) || !file_exists(public_path($setup['logo_img_url']->value))) {
            unset($setup['logo_img_url']);
        }

        // Get site title
        $site_title = !empty($setup['site_title']->value) ? $setup['site_title']->value : '';
        // Concat site title and meta title
        $meta_title = (array_diff(
            isset($share['page_data'])
                ? [$site_title, $share['page_data']->meta_title]
                : [$site_title],
            ['', null]));
        // Set meta title
        $setup->put('meta_title', implode(' | ', $meta_title));

        return $setup;
    }
}