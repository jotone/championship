<?php

namespace Tests\Mixins;

use App\Models\TestingEntity;
use Illuminate\Database\Eloquent\Model;
use Tests\TestCase;

class UnitTestCase extends TestCase
{
    /**
     * Testing data
     *
     * @var Model|null
     */
    protected static ?Model $model;

    /**
     * Testing class name
     *
     * @var string
     */
    protected static string $class;

    /**
     * Model creation default test
     *
     * @param $callback
     * @return void
     */
    protected function createModelTest($callback): void
    {
        self::$class = get_class(self::$model);

        $this->assertDatabaseHas('testing_entities', [
            'entity'    => self::$class,
            'entity_id' => self::$model->id
        ]);

        $callback();
    }

    /**
     * Model update default test
     *
     * @param string $table
     * @param array $fields
     * @return void
     */
    protected function updateModelTest(string $table, array $fields): void
    {
        $data = self::$class::factory()->make();

        $asserting_array = ['id' => self::$model->id];

        foreach ($fields as $field) {
            self::$model->$field = $data->$field;
            $asserting_array[$field] = $data->$field;
        }
        self::$model->save();

        $this->assertDatabaseHas($table, $asserting_array);
    }


    /**
     * Model remove default test
     *
     * @param string $table
     * @return void
     */
    protected function removeModelTest(string $table): void
    {
        foreach (TestingEntity::where('entity', self::$class)->get() as $entity) {
            self::$class::destroy($entity->entity_id);

            $this->assertDatabaseMissing($table, ['id' => $entity->entity_id]);
        }

        $this->assertDatabaseMissing('testing_entities', ['entity' => self::$class,]);

        self::$model = null;
    }
}