<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TutorAuthenticateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_guest_can_view_the_register_page()
    {
        $this->get('/tutors/register')
            ->assertViewIs('tutors.auth.register')
            ->assertSuccessful();
    }

    /**
     * @test
     */
    public function an_authenticated_tutor_may_not_view_the_register_page()
    {
        $this->signInAsTutor();

        $this->get('/tutors/register')
            ->assertRedirect('/tutors/home');
    }

    /**
     * @test
     */
    public function an_authenticated_user_may_not_view_the_register_page()
    {
        $this->signInAsUser();

        $this->get('/tutors/register')
            ->assertRedirect('/users/home');
    }

    /**
     * @test
     */
    public function a_guest_can_register_a_tutor()
    {
        $tutor = [
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'johndoe@email.nl',
            'password' => 'secret1234',
            'password_confirmation' => 'secret1234'
        ];

        $this->post('/tutors/register', $tutor);

        $this->assertAuthenticated('tutor');

        $this->assertDatabaseHas('tutors', array_column($tutor, 'email'));
    }

    /**
     * @test
     */
    public function a_guest_can_view_login_page()
    {
        $this->get('/tutors/login')
            ->assertViewIs('tutors.auth.login')
            ->assertSuccessful();
    }

    /**
     * @test
     */
    public function an_authenticated_tutor_may_not_view_the_login_page()
    {
        $this->signInAsTutor();

        $this->get('/tutors/login')
            ->assertRedirect('/tutors/home');
    }

    /**
     * @test
     */
    public function an_authenticated_user_may_not_view_the_login_page()
    {
        $this->signInAsUser();

        $this->get('/tutors/login')
            ->assertRedirect('/users/home');
    }

    /**
     * @test
     */
    public function a_tutor_can_login()
    {
        $tutor = create('App\Tutor', [
            'password' => bcrypt($password = 'secret1234')
        ]);

        $login_credentials = [
            'email' => $tutor->email,
            'password' => $password,
        ];

        $this->post('/tutors/login', $login_credentials)
            ->assertRedirect('/tutors/home');

        $this->assertAuthenticatedAs($tutor, 'tutor');
    }

    /**
     * @test
     */
    public function a_registered_tutor_cannot_log_in_with_the_wrong_password()
    {
        $this->withExceptionHandling();

        $tutor = create('App\Tutor', [
            'password' => bcrypt('secret1234')
        ]);

        $login_credentials = [
            'email' => $tutor->email,
            'password' => 'WRONGpassword'
        ];

        $this->post('/tutors/login', $login_credentials)
            ->assertSessionHasErrors('email');
    }

    /**
     * @test
     */
    public function a_tutor_can_choose_to_remember_login_credentials()
    {
        $tutor = create('App\Tutor', [
            'id' => random_int(1,100),
            'password' => bcrypt($password = 'secret1234')
        ]);

        $response = $this->post('/tutors/login', [
            'email' => $tutor->email,
            'password' => $password,
            'remember' => 'on'
        ]);

        $response->assertRedirect('/tutors/home');

        $response->assertCookie(Auth::guard('tutor')->getRecallerName(), vsprintf('%s|%s|%s', [
            $tutor->id,
            $tutor->getRememberToken(),
            $tutor->password,
        ]));

        $this->assertAuthenticatedAs($tutor, 'tutor');
    }

    /**
     * @test
     */
    public function a_tutor_can_logout()
    {
        $this->signInAsTutor();

        $this->post('/tutors/logout');

        $this->assertGuest('tutor');
        $this->assertGuest();
    }
}
