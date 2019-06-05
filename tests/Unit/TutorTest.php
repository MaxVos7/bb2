<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TutorTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_has_lessons()
    {
        $tutor = factory('App\Tutor')->create();

        factory('App\Lesson')->create([
            'tutor_id' => $tutor->id
        ]);

        $this->assertCount(1, $tutor->lessons);
    }
}
