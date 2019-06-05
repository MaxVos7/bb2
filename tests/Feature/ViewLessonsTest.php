<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewLessonsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_guest_may_not_see_lessons()
    {
        $this->withExceptionHandling();

        $lesson = create('App\Lesson');

        $this->get('/lessons')
            ->assertRedirect('/users/login');
        $this->get('/lessons/'.$lesson->id)
            ->assertRedirect('/users/login');
    }

    /**
     * @test
     */
    public function a_user_can_view_all_his_lessons()
    {
        $this->signInAsUser();

        $lessonNotByUser = create('App\Lesson');
        $lessonByUser = create('App\Lesson',[
            'user_id' => auth()->id()
        ]);

        $this->get('/lessons')
            ->assertSee($lessonByUser->date)
            ->assertDontSee($lessonNotByUser->date);
    }

    /**
     * @test
     */
    public function a_tutor_can_view_all_his_lessons()
    {
        $this->signInAsTutor();

        $lessonNotByTutor = create('App\Lesson');
        $lessonByTutor = create('App\Lesson', [
            'tutor_id' => auth()->id()
        ]);

        $this->get('/lessons')
            ->assertSee($lessonByTutor->date)
            ->assertDontSee($lessonNotByTutor->date);
    }

    /**
     * @test
     */
    public function a_user_can_view_one_of_his_lesson()
    {
        $this->signInAsUser();

        $lesson = create('App\Lesson', [
            'user_id' => auth()->id()
        ]);

        $this->get('/lessons/'.$lesson->id)
            ->assertSee($lesson->date);
    }

    /**
     * @test
     */
    public function a_tutor_can_view_one_of_his_lessons()
    {
        $this->signInAsTutor();

        $lesson = create('App\Lesson', [
            'tutor_id' => auth()->id()
        ]);

        $this->get('/lessons/'.$lesson->id)
            ->assertSee($lesson->date);
    }

    /**
     * @test
     */
    public function a_user_may_not_view_a_lesson_that_is_not_his()
    {
        $this->expectException('Illuminate\Auth\Access\AuthorizationException');

        $this->signInAsUser();

        $lesson = create('App\Lesson');

        $this->get('/lessons/'.$lesson->id);
    }
}
