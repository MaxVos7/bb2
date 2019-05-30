<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_has_lessons()
    {
        $user = factory('App\User')->create();

        factory('App\Lesson')->create([
            'user_id' => $user->id
        ]);

        $this->assertCount(1, $user->lessons);
    }
}
