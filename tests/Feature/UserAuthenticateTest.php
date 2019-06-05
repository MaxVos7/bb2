<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserAuthenticateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_guest_can_view_the_register_page()
    {
        $this->get('users/register')
            ->assertViewIs('auth.register')
            ->assertSuccessful();
    }

    /**
     * @test
     */
    public function an_authenticated_user_may_not_view_the_register_page()
    {
        $this->signInAsUser();

        $this->get('users/register')
            ->assertRedirect('users/home');
    }

    /**
     * @test
     */
    public function an_authenticated_tutor_may_not_view_the_register_page()
    {
        $this->signInAsTutor();

        $this->get('users/register')
            ->assertRedirect('/tutors/home');
    }

    /**
     * @test
     */
    public function a_guest_can_register_as_a_user()
    {
        $user = [
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'johndoe@email.nl',
            'password' => 'secret1234',
            'password_confirmation' => 'secret1234'
        ];

        $this->post('/users/register', $user);

        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', array_column($user, 'email'));
    }

    /**
     * @test
     */
    public function a_guest_can_view_login_page()
    {
        $this->get('users/login')
            ->assertViewIs('auth.login')
            ->assertSuccessful();
    }

    /**
     * @test
     */
    public function an_authenticated_user_may_not_see_the_login_page()
    {
        $this->signInAsUser();

        $this->get('users/login')
            ->assertRedirect('users/home');
    }

    /**
     * @test
     */
    public function an_authenticated_tutor_may_not_see_the_login_page()
    {
        $this->signInAsTutor();

        $this->get('/tutors/login')
            ->assertRedirect('/tutors/home');
    }

    /**
     * @test
     */
    public function a_user_can_login()
    {
        $user = create('App\User', [
            'password' => bcrypt($password = 'secret1234')
        ]);

        $login_credentials = [
            'email' => $user->email,
            'password' => $password
        ];

        $this->post('users/login', $login_credentials)
            ->assertRedirect('users/home');

        $this->assertAuthenticatedAs($user);
    }

    /**
     * @test
     */
    public function a_registered_user_cannot_login_with_a_wrong_password()
    {
        $this->withExceptionHandling();

        $user = create('App\User', [
            'password' => bcrypt($password = 'secret1234'),
        ]);

        $login_credentials = [
            'email' => $user->email,
            'password' => 'wrongPassword'
        ];

        $this->post('users/login', $login_credentials)
            ->assertSessionHasErrors('email');
    }

    /**
     * @test
     */
    public function a_user_can_choose_to_remember_login_credentials()
    {
        $user = create('App\User', [
            'id' => random_int(1,100),
            'password' => bcrypt($password = 'secret1234')
        ]);

        $response = $this->post('/users/login', [
            'email' => $user->email,
            'password' => $password,
            'remember' => 'on'
        ]);

        $response->assertRedirect('users/home');

        $response->assertCookie(Auth::guard()->getRecallerName(), vsprintf('%s|%s|%s', [
            $user->id,
            $user->getRememberToken(),
            $user->password,
        ]));

        $this->assertAuthenticatedAs($user);
    }

    /**
     * @test
     */
    public function a_user_can_logout()
    {
        $this->signInAsUser();

        $this->post('/users/logout');

        $this->assertGuest();
        $this->assertGuest('tutor');
    }
}
