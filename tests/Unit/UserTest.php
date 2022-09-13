<?php

namespace Tests\Unit;

use App\Models\{Role, User};
use Tests\Mixins\UnitTestCase;

class UserTest extends UnitTestCase
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
            self::$model = User::factory()->create();
        }
    }

    /**
     * User creating test
     *
     * @return void
     */
    public function testUserCreate(): void
    {
        $this->createModelTest(fn() => $this->assertDatabaseHas('users', [
            'name'    => self::$model->name,
            'email'   => self::$model->email,
            'img_url' => self::$model->img_url
        ]));
    }

    /**
     * User updating test
     *
     * @return void
     */
    public function testUserUpdate(): void
    {
        $this->updateModelTest('users', ['name', 'email']);
    }

    /**
     * Test user to role relation
     *
     * @return void
     */
    public function testUserToRoleRelation(): void
    {
        $role = Role::inRandomOrder()->first();
        self::$model->role_id = $role->id;
        self::$model->save();

        $this->assertTrue(self::$model->role->id == $role->id);
    }

    /**
     * User removing test
     *
     * @return void
     */
    public function testUserRemove(): void
    {
        $this->removeModelTest('users');
    }
}