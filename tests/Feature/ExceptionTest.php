<?php

namespace Tests\Feature;

use App\Exceptions\AuthException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ExceptionTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_returns_a_user_already_registered_exception(): void
    {
        $this->withoutExceptionHandling();

        $this->expectExceptionObject(
            AuthException::userAlreadyRegistered()
        );

        $this->get('/test');
    }
}
