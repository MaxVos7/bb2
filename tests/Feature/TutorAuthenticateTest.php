<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TutorAuthenticateTest extends TestCase
{
    /**
     * @test
     */
    public function a_guest_can_register_as_a_tutor()
    {
        $tutor = make('App\Tutor', [
            'password' => 'secret',
            'password_confirmation' => 'secret'
        ]);
        // Given a guest registers as tutor
        $this->post('/tutors/register', $tutor->toArray());
        // A row is added in the tutors table

        $this->assertDatabaseHas('tutors', $tutor->id);
        // Redirect to home with status
    }
}
