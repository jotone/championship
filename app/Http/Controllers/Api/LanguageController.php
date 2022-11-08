<?php

namespace App\Http\Controllers\Api;

use App\Classes\FileHelper;
use App\Http\Controllers\BasicApiController;
use App\Traits\LanguageTrait;
use App\Http\Requests\{LanguageStoreRequest, LanguageUpgradeRequest};
use App\Models\Settings;
use Illuminate\Http\{Request, Response};

class LanguageController extends BasicApiController
{
    use LanguageTrait;

    /**
     * View translation file content
     * @param string $lang
     * @param Request $request
     * @return Response
     */
    public function show(string $lang, Request $request): Response
    {
        $file = $request->get('file');

        $path = resource_path('lang/' . $lang . '/' . $file . '.php');
        abort_if(!$file || !file_exists($path), 404);

        return response(require $path);
    }

    /**
     * Create language package
     * @param LanguageStoreRequest $request
     * @return Response
     */
    public function store(LanguageStoreRequest $request): Response
    {
        $args = $request->only('lang');
        // Check if lang package already exists
        if (file_exists(resource_path('lang/' . $args['lang']))) {
            return response(['errors' => ['Вибрана мова вже встановлена']], 422);
        }
        // Check if folder already exists
        if (!is_dir(resource_path('lang/' . $args['lang']))) {
            FileHelper::createFolder(resource_path('lang/' . $args['lang']));
        }
        // Create package files
        $this->installLanguage(
            $args['lang'],
            json_decode(file_get_contents(app_path('Console/Commands/InstallationData/lang.json')), 1)
        );

        return response([], 201);
    }

    /**
     * Update language settings
     * @param LanguageUpgradeRequest $request
     * @return Response
     */
    public function upgrade(LanguageUpgradeRequest $request): Response
    {
        $args = $request->only(['admin_lang', 'main_lang', 'lang_list']);

        foreach ($args as $key => $val) {
            $setting = Settings::firstWhere(['key' => $key]);
            $setting->value = $val;
            $setting->save();
        }

        return response([]);
    }

    /**
     * Update translation value
     * @param string $lang
     * @param Request $request
     * @return Response
     */
    public function update(string $lang, Request $request): Response
    {
        $args = $request->only(['file', 'lang']);
        // Check if lang package already exists
        if (!file_exists(resource_path('lang/' . $lang))) {
            return response(['errors' => ['Вибрана мова не встановлена']], 422);
        }

        $file_path = resource_path('lang/'. $lang . '/' . $args['file'] . '.php');
        if (!file_exists($file_path)) {
            return response(['errors' => ['Вибраний файл не існує']], 422);
        }

        $file_content = require $file_path;

        foreach ($args['lang'] as $field => $rule) {
            if (is_array($rule)) {
                foreach ($rule as $inner_field => $string) {
                    $file_content[$field][$inner_field] = $string;
                }
            } else {
                $file_content[$field] = $rule;
            }
        }

        // Generate language file content
        $new_file_content = '';
        foreach ($file_content as $key => $value) {
            $new_file_content .= $this->processContent($key, $value);
        }

        file_put_contents(
            resource_path('lang/' . $lang . '/' . $args['file'] . '.php'),
            '<?php' . "\n\n" . 'return [' . "\n" . $new_file_content . '];'
        );

        return response([]);
    }

    /**
     * Remove language package
     * @param string $lang
     * @return Response
     */
    public function destroy(string $lang): Response
    {
        $setting = Settings::where('key', 'lang_list')->first();
        $values = array_flip($setting->converted_value);

        if (isset($values[$lang])) {
            unset($values[$lang]);
            $setting->value = array_flip($values);
            $setting->save();
        }

        if (is_dir(resource_path('lang/' . $lang))) {
            FileHelper::recursiveRemove(resource_path('lang/' . $lang));
        }

        return response([], 204);
    }
}