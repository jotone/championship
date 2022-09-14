<?php

namespace Database\Factories;

use App\Models\Competition;
use App\Traits\FactoryTrait;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class CompetitionFactory extends Factory
{
    use FactoryTrait;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Competition::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->company() . ' ' . uniqid();

        return [
            'name'          => $name,
            'slug'          => generateUrl($name),
            'groups_number' => mt_rand(1, 24),
            'img_url'       => fake()->imageUrl(),
            'bg_color'      => fake()->hexColor,
            'text_color'    => fake()->hexColor,
            'start_at'      => now(),
            'finish_at'     => now()->addMonths(mt_rand(1, 3)),
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