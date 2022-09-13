<?php

namespace Tests\Mixins;

use Illuminate\Support\Facades\DB;
use App\Models\{TestingEntity, User};
use Tests\TestCase;

class FeatureTestCase extends TestCase
{
    /**
     * Actor user for tests
     *
     * @var User
     */
    protected static User $user;

    /**
     * Actor user jwd token
     *
     * @var string
     */
    protected static string $token;

    /**
     * Setup the test environment.
     *
     * @return void
     * @throws \Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        if (!DB::table('users')->count()) {
            throw new \Exception('There are no users it the database.');
        }
        self::$user = User::select(['users.*', 'roles.slug'])
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->first();

        $this->withHeaders([
            'Authorization' => 'Bearer ' . auth('api')->login(self::$user),
            'Accept' => 'application/json'
        ]);
    }

    /**
     * Default list items request test
     *
     * @param string $class
     * @param string $route
     * @return void
     */
    protected function listItems(string $class, string $route): void
    {
        // Retrieve role list
        $response = $this->actingAs(self::$user)->get($route, );
        // Assert response status is 200 OK
        $response->assertOk();
        // Response content
        $content = json_decode($response->getContent());
        // Compare the request total roles number with the database query
        $this->assertEquals($class::count(), $content->total);
    }

    /**
     * Default remove item request test
     *
     * @param string $class
     * @param string $route
     * @param string $db_table
     * @return void
     */
    protected function removeItem(string $class, string $route, string $db_table): void
    {
        // Get testing role entity
        $model = TestingEntity::firstWhere('entity', $class)->relatedEntity;
        // Send the request to remove a role
        $response = $this->actingAs(self::$user)->delete(route($route, $model->id));
        // Assert response status is 204 No content
        $response->assertNoContent();
        // Check the database does not have the role record
        $this->assertDatabaseMissing($db_table, ['id' => $model->id]);
        // Check the testing entity removed
        $this->assertDatabaseMissing('testing_entities', [
            'entity'    => $class,
            'entity_id' => $model->id
        ]);
    }

    /**
     * Default page response test
     *
     * @param string $url
     * @param string $view
     * @return void
     */
    protected function pageResponse(string $url, string $view): void
    {
        $this->actingAs(self::$user)
            ->get($url)
            ->assertOk()
            ->assertViewIs($view);
    }
}