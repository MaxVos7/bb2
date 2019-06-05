<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();
    }

    /**
     * Sign user in or create one.
     *
     * @param null $user
     * @return TestCase
     */
    public function signInAsUser($user = null)
    {
        $user = $user ?: factory('App\User')->create();

        $this->actingAs($user);

        return $this;
    }

    /**
     * Sign user in or create one.
     *
     * @param null $user
     * @return TestCase
     */
    public function signInAsTutor($tutor = null)
    {
        $tutor = $tutor ?: factory('App\Tutor')->create();

        $this->actingAs($tutor, 'tutor');

        return $this;
    }
}
