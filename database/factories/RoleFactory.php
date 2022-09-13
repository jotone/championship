<?php

namespace Database\Factories;

use App\Models\Role;
use App\Traits\FactoryTrait;
use Illuminate\Database\Eloquent\{Factories\Factory, Model};
use Illuminate\Support\{Collection};

/**
 * @extends Factory<Role>
 */
class RoleFactory extends Factory
{
    use FactoryTrait;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Role::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->jobTitle() . ' ' . uniqid();

        return [
            'name'  => $name,
            'slug'  => generateUrl($name),
            'level' => 255
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