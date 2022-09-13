<?php

namespace App\Console\Commands;

use App\Classes\FileHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateBundle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:bundle {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Default stubs path
     *
     * @var string
     */
    protected string $stub_path = '';

    /**
     * Name singular form
     *
     * @var string
     */
    protected string $single = '';

    /**
     * Name plural form
     *
     * @var string
     */
    protected string $plural = '';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');
        // Create model
        $this->call('make:model', ['name' => $name,]);
        // Create migration
        $table = Str::snake(Str::pluralStudly(class_basename($name)));
        $this->call('make:migration', [
            'name'     => "create_{$table}_table",
            '--create' => $table,
        ]);

        $this->single = mb_strtolower($name);
        $this->plural = Str::plural($this->single);
        $this->stub_path = base_path('app/Console/Commands/BundleStubs/');
        $view_path = FileHelper::createFolder(resource_path('views/admin/' . $this->plural));
        $js_path = FileHelper::createFolder(resource_path('js/admin/' . $this->single . '-list'));

        // Create admin controller
        file_put_contents(
            base_path('app/Http/Controllers/Admin/' . $name . 'Controller.php'),
            $this->makeStubContent($name, 'AdminController.stub')
        );
        // Create api controller
        file_put_contents(
            base_path('app/Http/Controllers/Api/' . $name . 'Controller.php'),
            $this->makeStubContent($name, 'ApiController.stub')
        );
        // Create StoreRequest
        file_put_contents(
            base_path('app/Http/Requests/' . $name . 'StoreRequest.php'),
            $this->makeStubContent($name . 'Store', 'Request.stub')
        );
        // Create UpdateRequest
        file_put_contents(
            base_path('app/Http/Requests/' . $name . 'UpdateRequest.php'),
            $this->makeStubContent($name . 'Update', 'Request.stub')
        );
        // Create index list blade file
        file_put_contents(
            Str::finish($view_path, '/') . 'index.blade.php',
            $this->makeStubContent($name, 'views.index.stub')
        );
        // Create form blade file
        file_put_contents(
            Str::finish($view_path, '/') . 'form.blade.php',
            $this->makeStubContent($name, 'views.form.stub')
        );
        // Create vue app.js file
        file_put_contents(
            Str::finish($js_path, '/') . 'app.js',
            file_get_contents($this->stub_path . 'vue.app.stub')
        );
        // Create vue Main.vue file
        file_put_contents(
            Str::finish($js_path, '/') . 'Main.vue',
            $this->makeStubContent($name, 'vue.main.stub')
        );

        return 0;
    }

    /**
     * Fill stub content
     *
     * @param string $name
     * @param string $file
     * @return string
     */
    protected function makeStubContent(string $name, string $file): string
    {
        $admin_controller_content = preg_replace(
            '/%name%/', $name,
            file_get_contents($this->stub_path . $file)
        );

        $admin_controller_content = preg_replace('/%single%/', $this->single, $admin_controller_content);
        return preg_replace('/%plural%/', $this->plural, $admin_controller_content);
    }
}
