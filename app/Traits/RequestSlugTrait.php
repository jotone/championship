<?php

namespace App\Traits;

trait RequestSlugTrait
{
    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $slug = $this->request->get('slug', $this->request->get('name'));

        $this->merge([
            'slug' => generateUrl(!empty($slug) ? $slug : $this->request->get('name'))
        ]);
    }
}