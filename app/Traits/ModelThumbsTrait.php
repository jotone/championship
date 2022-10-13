<?php

namespace App\Traits;

trait ModelThumbsTrait
{
    /**
     * Get thumbs images
     *
     * @return array
     */
    public function getThumbsAttribute(): array
    {
        // Check image exists
        if (empty($this->attributes['img_url'])) {
            return [];
        }
        $result = [];
        // Get file name
        $filename = pathinfo($this->attributes['img_url'], PATHINFO_BASENAME);
        // Check thumbs exist
        foreach (['large', 'small'] as $type) {
            $path = 'uploads/thumbs/' . $this->settings_key . '/' . $type . '/' . $filename;
            if (file_exists(public_path($path))) {
                $result[$type] = $path;
            }
        }

        return $result;
    }
}