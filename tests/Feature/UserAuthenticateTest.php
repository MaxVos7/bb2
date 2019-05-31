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
    public function a_guest_can_view_login_page()
    {
        $this->get('/login')
            ->assertViewIs('auth.login')
            ->assertSuccessful();
    }

    /**
     * @test
     */
    public function an_authenticated_user_may_not_see_the_login_page()
    {
        $this->signIn();

        $this->get('/login')
            ->assertRedirect('/home');
    }

    /**
     * @test
     */
    public function a_guest_can_register_a_user()
    {
        $user = [
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'johndoe@email.nl',
            'password' => 'secret1234',
            'password_confirmation' => 'secret1234'
        ];

        $this->post('/register', $user);

        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', array_column($user, 'email'));
    }

    /**
     * @test
     */
    public function a_registered_user_can_login()
    {
        $user = create('App\User', [
            'password' => bcrypt($password = 'secret1234')
        ]);

        $login_credentials = [
            'email' => $user->email,
            'password' => $password
        ];

        $this->post('/login', $login_credentials)
            ->assertRedirect('/home');

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

        $this->post('/login', $login_credentials)
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

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => $password,
            'remember' => 'on'
        ]);

        $response->assertRedirect('/home');

        $response->assertCookie(Auth::guard()->getRecallerName(), vsprintf('%s|%s|%s', [
            $user->id,
            $user->getRememberToken(),
            $user->password,
        ]));

        $this->assertAuthenticatedAs($user);
    }
}
