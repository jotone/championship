<?php

namespace App\Console\Commands;

use App\Models\{AdminMenu, CustomPage, Permission, Role, Settings, User};
use App\Traits\PermissionsTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InstallApplication extends Command
{
    use PermissionsTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Installation data files path
     *
     * @var string
     */
    protected $files_path = 'app/Console/Commands/InstallationData/';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // retrieve installation data
        $files = [
            'admin_menu' => json_decode(file_get_contents(base_path($this->files_path . 'admin_menu.json')), 1),
            'countries'  => json_decode(file_get_contents(base_path($this->files_path . 'countries.json')), 1),
            'roles'      => json_decode(file_get_contents(base_path($this->files_path . 'roles.json')), 1),
            'settings'   => json_decode(file_get_contents(base_path($this->files_path . 'settings.json')), 1),
            'pages'      => json_decode(file_get_contents(base_path($this->files_path . 'pages.json')), 1),
        ];

        // Create admin menu
        $this->runWithTimer('Dashboard side menu', function () use ($files) {
            foreach ($files['admin_menu'] as $position => $item) {
                $this->createAdminMenuItem($position, $item);
            }
        });

        // Create user roles
        $roles = $this->runWithTimer('Application user roles', function () use ($files) {
            $roles = [];
            foreach ($files['roles'] as $role_data) {
                $roles[$role_data['slug']] = Role::create($role_data);

                if (isset($role_data['permissions'])) {
                    foreach ($role_data['permissions'] as $permission) {
                        Permission::create([
                            'role_id'         => $roles[$role_data['slug']]->id,
                            'controller'      => $permission['controller'],
                            'allowed_methods' => $permission['methods']
                        ]);
                    }
                }
            }
            return $roles;
        });

        // Create role permissions
        $this->runWithTimer('Application role permissions', function () use ($roles) {
            $permissions = $this->permissionList(['app/Http/Controllers/Admin', 'app/Http/Controllers/Api']);
            $n = count($permissions);
            for ($i = 0; $i < $n; $i++) {
                Permission::create([
                    'role_id'         => $roles['superadmin']->id,
                    'controller'      => $permissions[$i]['class'],
                    'allowed_methods' => $permissions[$i]['methods']
                ]);
            }
        });

        // Create default users
        $this->runWithTimer('Superuser account', function () use ($roles) {
            User::create([
                'name'              => 'Superuser',
                'email'             => 'hereyouare1987@gmail.com',
                'password'          => '123456',
                'role_id'           => $roles['superadmin']->id,
                'img_url'           => '/images/noname_big.jpg',
                'email_verified_at' => now()
            ]);
        });

        // Create settings
        $this->runWithTimer('Create Settings', function () use ($files) {
            foreach ($files['settings'] as $section => $settings) {
                foreach ($settings as $i => $setting) {
                    Settings::create([
                        'key'      => $setting['key'],
                        'value'    => $setting['value'],
                        'section'  => $section,
                        'caption'  => $setting['caption'],
                        'position' => $i
                    ]);
                }
            }
        });

        // Create countries
        $this->runWithTimer('Countries', function () use ($files) {
            $n = count($files['countries']);
            for ($i = 0; $i < $n; $i++) {
                DB::table('countries')->insert([
                    'code'    => $files['countries'][$i]['code'],
                    'en'      => $files['countries'][$i]['en'],
                    'ua'      => $files['countries'][$i]['ua'],
                    'img_url' => $files['countries'][$i]['img_url']
                ]);
            }
        });

        // Create pages
        $this->runWithTimer('Pages', function () use ($files) {
            $n = count($files['pages']);
            for ($i = 0; $i < $n; $i++) {
                CustomPage::create($files['pages'][$i]);
            }
        });
        return 0;
    }

    /**
     * Run function with microseconds timer
     * @param string $message
     * @param $callback
     * @return mixed
     */
    protected function runWithTimer(string $message, $callback)
    {
        // Get current timestamp
        $timestamp = microtime(true);
        // Show console message
        $this->line('<fg=yellow>Creating:</> ' . $message);
        // Run routine
        $result = $callback();
        // Show the complete message
        $this->line('<fg=green>Created:</> ' . $message . ' (' . number_format((microtime(true) - $timestamp) * 1000, 2) . 'ms)');

        return $result;
    }

    /**
     * Process admin menu data
     * @param int $position
     * @param array $data
     * @param null $parent_id
     */
    protected function createAdminMenuItem(int $position, array $data, $parent_id = null)
    {
        $menu = AdminMenu::create([
            'name'       => $data['name'],
            'url'        => $data['url'] ?? '#',
            'img_url'    => $data['img_url'] ?? null,
            'parent_id'  => $parent_id,
            'position'   => $position,
            'is_section' => isset($data['inner'])
        ]);

        if (!empty($data['inner'])) {
            foreach ($data['inner'] as $position => $item) {
                $this->createAdminMenuItem($position, $item, $menu->id);
            }
        }
    }
}
