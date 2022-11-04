<?php

namespace App\Http\Controllers\Admin;

use App\Classes\FileHelper;
use App\Http\Controllers\BasicAdminController;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\View\View;
use PHP_ICO;
use SVG\SVG;

class SettingsController extends BasicAdminController
{
    /**
     * Main settings page
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        return $this->renderPage('admin.settings.index', $request, [
            'content' => Settings::where('section', 'common')->get()->keyBy('key'),
            'title'   => 'Основні налаштування'
        ]);
    }

    public function update(Request $request)
    {
        // Request data
        $args = $request->only([
            'site_title',
            'fav_icon',
            'header_img_url',
            'logo_img_url',
            'date_format',
            'comment_date_format'
        ]);
        // Rules array
        $rules = [];
        // Data array
        $data = [];

        foreach ($args as $key => $val) {
            switch ($key) {
                case 'date_format':
                case 'comment_date_format':
                case 'site_title':
                    $rules[$key] = ['required', 'string'];
                    $data[$key] = $val;
                    break;
                case 'header_img_url':
                case 'logo_img_url':
                    $rules[$key] = ['required'];
                    $url = FileHelper::saveFile($args[$key], 'images');
                    $pathinfo = pathinfo($url);
                    $filename = $key === 'header_img_url' ? 'header' : 'logo';
                    $img_url = sprintf('%s/%s.%s', $pathinfo['dirname'], $filename, $pathinfo['extension']);
                    rename(public_path($url), public_path($img_url));
                    $data[$key] = $img_url;
                    break;
                case 'fav_icon':
                    $url = FileHelper::saveFile($val, 'favicon');

                    if ($args['fav_icon']->getClientMimeType() == 'image/svg+xml') {
                        $rules[$key] = ['required', 'string'];

                        $dir = Str::start('/', pathinfo($url, PATHINFO_DIRNAME));
                        $img_url = $dir . '/icon.svg';

                        $full_path = public_path($img_url);
                        rename(public_path($url), $full_path);

                        $this->svgToPng($full_path, public_path($dir . '/icon-32.png'), [32, 32]);
                        $this->svgToPng($full_path, public_path($dir . '/icon-512.png'), [512, 512]);
                        $this->svgToPng($full_path, public_path($dir . '/icon-192.png'), [192, 192]);
                        $this->svgToPng($full_path, public_path($dir . '/apple-touch-icon.png'), [180, 180]);

                        $ico = new PHP_ICO(public_path($dir . '/icon-32.png'));
                        $ico->save_ico(public_path($dir . '/favicon.ico'));

                        file_put_contents(public_path($dir . '/manifest.webmanifest'), json_encode([
                            'icons' => [
                                [
                                    'src' => $dir. '/icon-192.png',
                                    'type' => 'image/png',
                                    'sizes' => '192x192'
                                ],
                                [
                                    'src' => $dir. '/icon-512.png',
                                    'type' => 'image/png',
                                    'sizes' => '512x512'
                                ]
                            ],
                        ]));
                    } else {
                        $rules[$key] = ['required', 'file', 'mimes:ico'];
                        $img_url = $url;
                    }

                    $data[$key] = $img_url;
                    break;
            }
        }

        // Run request validation
        $validation = Validator::make($data, $rules);

        if ($validation->fails()) {
            if (isset($args['header_img_url'])) {
                FileHelper::recursiveRemove(public_path($data['header_img_url']));
            }
            if (isset($args['logo_img_url'])) {
                FileHelper::recursiveRemove(public_path($data['logo_img_url']));
            }
            if (isset($args['fav_icon'])) {
                FileHelper::recursiveRemove(public_path('/favicon'));
            }

            return response($validation->errors()->all(), 422);
        }
        // Update settings values
        foreach ($data as $key => $val) {
            $model = Settings::firstWhere('key', $key);
            $model->value = $val;
            $model->save();
        }

        return response(Settings::whereIn('key', array_keys($args))->get()->toArray());
    }

    /**
     * Convert svg to png
     * @param string $from
     * @param string $to
     * @param array $dimensions
     * @return string
     */
    public function svgToPng(string $from, string $to, array $dimensions): string
    {
        $svg = SVG::fromFile($from);
        $raster_image = $svg->toRasterImage($dimensions[0], $dimensions[1]);
        imagepng($raster_image, $to);
        return $to;
    }
}