<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\Team;
use App\Traits\FactoryTrait;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @extends Factory<Team>
 */
class TeamFactory extends Factory
{
    use FactoryTrait;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Team::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $country = Country::inRandomOrder()->first();
        $name = fake()->country . ' ' . fake()->company;
        return [
            'en'         => $name,
            'ua'         => $name,
            'country_id' => $country->id,
            'img_url'    => fake()->imageUrl()
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