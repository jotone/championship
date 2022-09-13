<?php

namespace Tests\Feature;

use App\Models\{TestingEntity, User};
use Tests\Mixins\FeatureTestCase;

class UserTest extends FeatureTestCase
{
    /**
     * Get user list
     *
     * @return void
     */
    public function testUserList(): void
    {
        $this->listItems(User::class, route('api.users.index'));
    }

    /**
     * Create user
     *
     * @return void
     */
    public function testUserStore(): void
    {
        // Generate the user data
        $data = User::factory()->make(['img_url' => null])->toArray();
        $data['password'] = '123456';
        $data['confirmation'] = '123456';
        // Send the request to create a user
        $response = $this->post(route('api.users.store'), $data);
        // Assert response status is 201 Created
        $response->assertCreated();
        // Check the database has the generated user data
        $this->assertDatabaseHas('users', [
            'name'    => $data['name'],
            'email'   => $data['email'],
            'role_id' => $data['role_id']
        ]);
        // Response content
        $content = json_decode($response->getContent());
        // Compare the response data with the generated user data
        $this->assertTrue($data['name'] == $content->name);
        $this->assertTrue($data['email'] == $content->email);
        $this->assertTrue($data['role_id'] == $content->role_id);

        TestingEntity::create([
            'entity'    => User::class,
            'entity_id' => $content->id
        ]);
    }

    /**
     * Update user
     *
     * @return void
     */
    public function testUserUpdate(): void
    {
        // Get testing user entity
        $user = TestingEntity::firstWhere('entity', User::class)->relatedEntity;
        // Generate the user data
        $data = User::factory()->make(['img_url' => null])->toArray();
        // Send the request to update a user
        $response = $this->put(route('api.users.update', $user->id), $data);
        // Assert response status is 200 OK
        $response->assertOk();
        // Check the database has the generated user data
        $this->assertDatabaseHas('users', [
            'id'      => $user->id,
            'name'    => $data['name'],
            'email'   => $data['email'],
            'role_id' => $data['role_id']
        ]);
        // Response content
        $content = json_decode($response->getContent());
        // Compare the response data with the generated user data
        $this->assertTrue($user->id == $content->id);
        $this->assertTrue($data['name'] == $content->name);
        $this->assertTrue($data['email'] == $content->email);
        $this->assertTrue($data['role_id'] == $content->role_id);
    }

    /**
     * Remove user
     *
     * @return void
     */
    public function testUserDelete(): void
    {
        $this->removeItem(User::class, 'api.users.destroy', 'users');
    }

    /**
     * Check users index page response
     *
     * @return void
     */
    public function testUserIndexPage(): void
    {
        $this->pageResponse(route('admin.users.index'), 'admin.users.index');
    }

    /**
     * Check user create page response
     *
     * @return void
     */
    public function testUserCreatePage(): void
    {
        $this->pageResponse(route('admin.users.create'), 'admin.users.form');
    }

    /**
     * Check user edit page response
     *
     * @return void
     */
    public function testUserEditPage(): void
    {
        $this->pageResponse(route('admin.users.edit', User::inRandomOrder()->value('id')), 'admin.users.form');
    }
}