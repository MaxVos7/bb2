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

        $lesson = factory('App\Lesson')->create();

        $this->get('/lessons')
            ->assertRedirect('/login');
        $this->get('/lessons/'.$lesson->id)
            ->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function a_user_can_view_all_his_lessons()
    {
        $this->signIn();

        $lessonNotByUser = factory('App\Lesson')->create();
        $lessonByUser = factory('App\Lesson')->create([
            'user_id' => auth()->id()
        ]);

        $this->get('/lessons')
            ->assertSee($lessonByUser->date)
            ->assertDontSee($lessonNotByUser->date);
    }

    /**
     * @test
     */
    public function a_user_can_view_one_of_his_lesson()
    {
        $this->signIn();

        $lesson = factory('App\Lesson')->create([
            'user_id' => auth()->id()
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

        $this->signIn();

        $lesson = factory('App\Lesson')->create();

        $this->get('/lessons/'.$lesson->id);
    }
}
