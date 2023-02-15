<?php

namespace Tests\Feature\Rules;

use App\Rules\Password;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class PasswordTest extends TestCase
{
    public function testAcceptsValidPasswords(): void
    {
        $validator = Validator::make(['password' => 'p@ssW0rd'], [
            'password' => new Password(),
        ]);

        $this->assertTrue(
            $validator->passes()
        );
    }

    public function testRejectsInvalidPasswords(): void
    {
        $validator = Validator::make(['password' => 'password'], [
            'password' => new Password(),
        ]);

        $this->assertFalse(
            $validator->passes()
        );
    }
}
