<?php

namespace Tests\Feature;

use App\Models\{Role, TestingEntity};
use Tests\Mixins\FeatureTestCase;

class RoleTest extends FeatureTestCase
{
    /**
     * Get role list
     *
     * @return void
     */
    public function testRoleList(): void
    {
        $this->listItems(Role::class, route('api.roles.index'));
    }

    /**
     * Create role
     *
     * @return void
     */
    public function testRoleStore(): void
    {
        // Generate the role data
        $data = Role::factory()->make()->toArray();
        // Send the request to create a role
        $response = $this->post(route('api.roles.store'), $data);
        // Assert response status is 201 Created
        $response->assertCreated();
        // Check the database has the generated role data
        $this->assertDatabaseHas('roles', $data);
        // Response content
        $content = json_decode($response->getContent());
        // Compare the response data with the generated role data
        $this->assertTrue($data == [
            'name'  => $content->name,
            'slug'  => $content->slug,
            'level' => $content->level
        ]);

        TestingEntity::create([
            'entity'    => Role::class,
            'entity_id' => $content->id
        ]);
    }

    /**
     * Update role
     *
     * @return void
     */
    public function testRoleUpdate(): void
    {
        // Get testing role entity
        $role = TestingEntity::firstWhere('entity', Role::class)->relatedEntity;
        // Generate the role data
        $data = Role::factory()->make()->toArray();
        $data['slug'] = $role->slug;
        // Send the request to update a role
        $response = $this->put(route('api.roles.update', $role->id), $data);
        // Assert response status is 200 OK
        $response->assertOk();
        // Check the database has the generated role data
        $data = array_merge(['id' => $role->id], $data);
        $this->assertDatabaseHas('roles', $data);
        // Response content
        $content = json_decode($response->getContent());
        // Compare the response data with the generated role data
        $this->assertTrue($data == [
            'id'    => $content->id,
            'name'  => $content->name,
            'slug'  => $content->slug,
            'level' => $content->level
        ]);
    }

    /**
     * Remove role
     *
     * @return void
     */
    public function testRoleDelete(): void
    {
        $this->removeItem(Role::class, 'api.roles.destroy', 'roles');
    }

    /**
     * Check roles index page response
     *
     * @return void
     */
    public function testRoleIndexPage(): void
    {
        $this->pageResponse(route('admin.users.roles.index'), 'admin.roles.index');
    }

    /**
     * Check role create page response
     *
     * @return void
     */
    public function testRoleCreatePage(): void
    {
        $this->pageResponse(route('admin.users.roles.create'), 'admin.roles.form');
    }

    /**
     * Check role edit page response
     *
     * @return void
     */
    public function testRoleEditPage(): void
    {
        $this->pageResponse(route('admin.users.roles.edit', Role::inRandomOrder()->value('id')), 'admin.roles.form');
    }
}
