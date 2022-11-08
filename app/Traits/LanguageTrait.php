<?php

namespace App\Traits;

use App\Classes\FileHelper;

trait LanguageTrait
{
    /**
     * Available language packages
     * @var string[]
     */
    public $language_list = [
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
        'km',
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
     * Build lang file content
     * @param string $key
     * @param $value
     * @param array $locale
     * @param string $shift
     * @return string
     */
    protected function processContent(string $key, $value, array $locale = [], string $shift = '    '): string
    {
        $result = '';
        if (is_array($value)) {
            $temp = '';
            foreach ($value as $inner_key => $inner_value) {
                $temp .= $this->processContent($inner_key, $inner_value, $locale, $shift . '    ');
            }
            $result .= "$shift'$key' => [\n$temp$shift],\n";
        } else {
            if (!empty($locale)) {
                $value = $locale[$key];
            }
            $value = preg_replace('/\'/', '&apos;', $value);
            $result .= "$shift'$key' => '$value',\n";
        }
        return $result;
    }

    /**
     * Create language files
     * @param string $lang
     * @param array $translations
     * @return void
     */
    protected function installLanguage(string $lang, array $translations)
    {
        // Check if the language folder exists
        if (!is_dir(resource_path('lang/' . $lang))) {
            FileHelper::createFolder(resource_path('lang/' . $lang));
        }
        // Process language file data
        foreach ($translations as $file_name => $data) {
            // Get translations for foreign languages
            $locale = $lang != 'en'
                ? json_decode(file_get_contents(base_path('vendor/laravel-lang/lang/locales/' . $lang . '/php.json')), 1)
                : [];
            // Generate language file content
            $new_file_content = '';
            foreach ($data as $key => $value) {
                $new_file_content .= $this->processContent($key, $value, $locale);
            }

            file_put_contents(
                resource_path('lang/' . $lang . '/' . $file_name . '.php'),
                '<?php' . "\n\n" . 'return [' . "\n" . $new_file_content . '];'
            );
        }
    }
}