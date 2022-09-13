<?php

namespace Tests\Unit;

use App\Models\Permission;
use Tests\Mixins\UnitTestCase;

class PermissionTest extends UnitTestCase
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        if (empty(self::$model)) {
            self::$model = Permission::factory()->create();
        }
    }

    /**
     * Permission creating test
     *
     * @return void
     */
    public function testPermissionCreate(): void
    {
        $this->createModelTest(fn() => $this->assertDatabaseHas('permissions', [
            'role_id'    => self::$model->role_id,
            'controller' => self::$model->controller
        ]));
    }

    /**
     * Permission updating test
     *
     * @return void
     */
    public function testPermissionModify(): void
    {
        $this->updateModelTest('permissions', ['role_id', 'controller']);
    }

    /**
     * Permission removing test
     *
     * @return void
     */
    public function testPermissionRemove(): void
    {
        $this->removeModelTest('permissions');
    }
}