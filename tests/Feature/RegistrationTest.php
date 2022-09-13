<?php

namespace Tests\Feature;

use App\Models\{Role, TestingEntity, User};
use Illuminate\Support\Str;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    /**
     * Testing user
     *
     * @var User
     */
    protected static User $user;

    /**
     * Registration page response
     *
     * @return void
     */
    public function testRegistrationFormResponse(): void
    {
        $this->get(route('registration.index'))
            ->assertOk()
            ->assertViewIs('main.registration.index');
    }

    /**
     * Registration form validation
     *
     * @return void
     */
    public function testRegistrationFormValidation(): void
    {
        $this->post(route('registration.store'), [
            'name'         => Str::random(),
            'email'        => Str::random(),
            'password'     => null,
            'confirmation' => Str::random()
        ])->assertJsonValidationErrors([
            'email',
            'password',
            'confirmation'
        ])->assertUnprocessable();
    }

    /**
     * Test the registration form creates a user
     *
     * @return void
     */
    public function testRegistrationForm(): void
    {
        $user = User::factory()->make();
        $this->post(route('registration.store'), [
            'name'         => $user->name,
            'email'        => $user->email,
            'password'     => 'password',
            'confirmation' => 'password'
        ])->assertViewIs('main.registration.thank-you')->assertOk();

        $this->assertDatabaseHas('users', [
            'name'    => $user->name,
            'email'   => $user->email,
            'role_id' => Role::where('slug', 'regular')->value('id')
        ]);

        self::$user = User::firstWhere('email', $user->email);

        TestingEntity::create([
            'entity'    => User::class,
            'entity_id' => self::$user->id
        ]);
    }

    /**
     * Registration email confirmation page response
     *
     * @return void
     */
    public function testRegistrationEmailConfirmation(): void
    {
        // Set random token
        $this->get(route('registration.confirmation', Str::random(64)))->assertNotFound();

        // Set user token
        $this->get(route('registration.confirmation', self::$user->emailVerification->token))
            ->assertViewIs('main.registration.confirmation')
            ->assertOk();
    }
}