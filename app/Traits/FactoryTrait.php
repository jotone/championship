<?php

namespace App\Traits;

use App\Models\TestingEntity;
use Illuminate\Support\Collection;

trait FactoryTrait
{
    /**
     * @param Collection $instances
     *
     * @return void
     */
    public function fillTestDataTable(Collection $instances): void
    {
        $instances->each(function ($model) {
            TestingEntity::create([
                'entity'    => $this->model,
                'entity_id' => $model->id
            ]);
        });
    }
}