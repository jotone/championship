<?php

namespace Database\Factories;

use App\Models\{Role, User};
use App\Traits\FactoryTrait;
use Illuminate\Database\Eloquent\{Factories\Factory, Model};
use Illuminate\Support\{Collection, Str};

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    use FactoryTrait;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'              => fake()->name(),
            'email'             => fake()->safeEmail(),
            'email_verified_at' => now(),
            'password'          => bcrypt('123456'),
            'img_url'           => fake()->imageUrl(),
            'remember_token'    => Str::random(10),
            'role_id'           => Role::where('slug', 'regular')->value('id')
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    /**
     * Call the "after creating" callbacks for the given model instances.
     *
     * @param  Collection  $instances
     * @param  Model|null  $parent
     * @return void
     */
    public function callAfterCreating(Collection $instances, ?Model $parent = null): void
    {
        $this->fillTestDataTable($instances);
        parent::callAfterCreating($instances, $parent);
    }
}
