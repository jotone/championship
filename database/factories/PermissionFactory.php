<?php

namespace Database\Factories;

use App\Models\{Permission, Role};
use App\Traits\FactoryTrait;
use Illuminate\Database\Eloquent\{Factories\Factory, Model};
use Illuminate\Support\Collection;

/**
 * @extends Factory<Permission>
 */
class PermissionFactory extends Factory
{
    use FactoryTrait;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Permission::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $role = Role::where('slug', '!=', 'superadmin')->inRandomOrder()->first();

        return [
            'role_id'         => $role->id,
            'controller'      => 'App\Http\Controllers\Main\AuthController',
            'allowed_methods' => ['index']
        ];
    }

    /**
     * Call the "after creating" callbacks for the given model instances.
     *
     * @param Collection $instances
     * @param Model|null $parent
     * @return void
     */
    public function callAfterCreating(Collection $instances, ?Model $parent = null): void
    {
        $this->fillTestDataTable($instances);
        parent::callAfterCreating($instances, $parent);
    }
}