<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BasicAdminController;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\View\View;
use PeterColes\Languages\LanguagesFacade;

class LanguageController extends BasicAdminController
{
    /**
     * Available language packages
     * @var string[]
     */
    public $languages = [
        'af',
        'ar',
        'az',
        'be',
        'bg',
        'bn',
        'bs',
        'ca',
        'cs',
        'cy',
        'da',
        'de',
        'el',
        'en',
        'es',
        'et',
        'eu',
        'fa',
        'fi',
        'fr',
        'gl',
        'gu',
        'he',
        'hi',
        'hr',
        'hu',
        'hy',
        'id',
        'is',
        'it',
        'ja',
        'ka',
        'kk',
        'km' => 'Central Khmer',
        'kn',
        'ko',
        'lt',
        'lv',
        'mk',
        'mn',
        'mr',
        'ms',
        'nb',
        'ne',
        'nl',
        'nn',
        'oc',
        'pl',
        'ps',
        'pt',
        'ro',
        'ru',
        'rw',
        'sc',
        'si',
        'sk',
        'sl',
        'sq',
        'sr',
        'sv',
        'sw',
        'tg',
        'th',
        'tr',
        'uk',
        'ur',
        'uz',
        'vi',
        'zh',
    ];

    /**
     * Main settings page
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $settings = Settings::whereIn('key', ['admin_lang', 'main_lang', 'lang_list'])->get()->keyBy('key');
        return $this->renderPage('admin.settings.languages', $request, [
            'content' => $settings,
            'langs'   => LanguagesFacade::lookup($this->languages, $settings['admin_lang']->value),
            'title'   => 'Налаштування мови'
        ]);
    }
}