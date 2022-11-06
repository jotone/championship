<?php

namespace App\Traits;

trait SettingsTrait
{
    /**
     * Generate override css file content
     * @param $data
     * @return void
     * @throws \Throwable
     */
    protected function generateOverrideCSS($data)
    {
        $html = view('admin.settings.css-generate', ['data' => $data])->render();
        file_put_contents(public_path('/css/override.css'), $html);
    }
}