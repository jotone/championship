<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Str;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    /**
     * Test user
     *
     * @var User
     */
    protected static User $user;

    /**
     * Test Forgot-Password page response
     *
     * @return void
     */
    public function testForgotPasswordPageResponse(): void
    {
        $this->get(route('password-reset.index'))
            ->assertViewIs('main.password-reset.index')
            ->assertOk();
    }

    /**
     * Test Forgot-Password form validation
     *
     * @return void
     */
    public function testForgotPasswordFormValidation(): void
    {
        $email = Str::random() . '@mail.com';
        $this->post(route('password-reset.store'), ['email' => $email])
            ->assertViewIs('main.password-reset.report')
            ->assertSeeText('User with such email has not been found')
            ->assertDontSeeText('The letter with the further instructions was send to your email')
            ->assertOk();

        $this->assertDatabaseMissing('password_resets', ['email' => $email]);
    }

    /**
     * Test Forgot-Password form
     *
     * @return void
     */
    public function testForgotPasswordForm(): void
    {
        self::$user = User::factory()->create();

        $this->post(route('password-reset.store'), ['email' => self::$user->email])
            ->assertViewIs('main.password-reset.report')
            ->assertDontSeeText('User with such email has not been found')
            ->assertSeeText('The letter with the further instructions was send to your email')
            ->assertOk();

        $this->assertDatabaseHas('password_resets', ['email' => self::$user->email]);
    }

    /**
     * Test Password Reset page response
     *
     * @return void
     */
    public function testPasswordResetPage(): void
    {
        $this->get(route('password-reset.form', self::$user->passwordReset->token))
            ->assertViewIs('main.password-reset.form')
            ->assertOk();
    }

    /**
     * Test Password Reset form validation
     *
     * @return void
     */
    public function testPasswordResetFormValidation(): void
    {
        $this->post(route('password-reset.update', Str::random()))
            ->assertJsonValidationErrors(['password', 'confirmation'])
            ->assertUnprocessable();

        $this->post(route('password-reset.update', Str::random()), [
            'password'     => 'password',
            'confirmation' => 'password'
        ])->assertNotFound();
    }

    /**
     * Test Password Reset form
     *
     * @return void
     */
    public function testPasswordResetForm(): void
    {
        $this->post(route('password-reset.update', self::$user->passwordReset->token), [
            'password'     => 'password',
            'confirmation' => 'password'
        ])
            ->assertViewIs('main.password-reset.success')
            ->assertOk();

        $this->assertDatabaseMissing('password_resets', [
            'email' => self::$user->email
        ]);
    }
}