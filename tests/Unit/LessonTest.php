<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LessonTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_has_a_user()
    {
        $lesson = factory('App\Lesson')->create();

        $this->assertInstanceOf('App\User', $lesson->user);
    }

    /**
     * @test
     */
    public function it_has_a_tutor()
    {
        $lesson = factory('App\Lesson')->create();

        $this->assertInstanceOf('App\Tutor', $lesson->tutor);
    }
}
