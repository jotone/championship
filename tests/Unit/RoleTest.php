<?php

namespace Tests\Unit;

use App\Models\{Permission, Role, User};
use Tests\Mixins\UnitTestCase;

class RoleTest extends UnitTestCase
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
            self::$model = Role::factory()->create();
        }
    }

    /**
     * Role creating test
     *
     * @return void
     */
    public function testRoleCreate(): void
    {
        $this->createModelTest(fn() => $this->assertDatabaseHas('roles', [
            'name'  => self::$model->name,
            'slug'  => self::$model->slug,
            'level' => self::$model->level
        ]));
    }

    /**
     * Role updating test
     *
     * @return void
     */
    public function testRoleModify(): void
    {
        $this->updateModelTest('roles', ['name', 'slug']);
    }

    /**
     * Test role to permissions relation
     *
     * @return void
     */
    public function testRolePermissionsRelation(): void
    {
        $permission = Permission::factory()->create([
            'role_id' => self::$model->id
        ]);

        $this->assertDatabaseHas('permissions', [
            'id'         => $permission->id,
            'role_id'    => self::$model->id,
            'controller' => 'App\Http\Controllers\Main\AuthController'
        ]);

        $this->assertTrue(in_array($permission->id, self::$model->permissions->pluck('id')->toArray()));
    }

    /**
     * Test role to users relation
     *
     * @return void
     */
    public function testRoleRelationUsers(): void
    {
        $user = User::factory()->create([
            'role_id' => self::$model->id
        ]);

        $this->assertDatabaseHas('users', [
            'id'      => $user->id,
            'role_id' => self::$model->id
        ]);

        $this->assertTrue(in_array($user->id, self::$model->users->pluck('id')->toArray()));
    }

    /**
     * Role removing test
     *
     * @return void
     */
    public function testRoleRemove(): void
    {
        $this->removeModelTest('roles');
    }
}
