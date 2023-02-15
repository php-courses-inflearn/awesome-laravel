<?php

namespace Tests\Feature\Providers;

use Illuminate\Contracts\Validation\UncompromisedVerifier;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Tests\TestCase;

class PasswordServiceProviderTest extends TestCase
{
    public function testPasswordRule(): void
    {
        $validator = Validator::make(['password' => 'password'], [
            'password' => Password::default(),
        ]);

        $this->assertTrue(
            $validator->passes()
        );
    }

    public function testPasswordRuleInProduction(): void
    {
        $this->app->bind('env', function () {
            return 'production';
        });

        $this->mock(UncompromisedVerifier::class, function ($mock) {
            $mock->shouldReceive('verify')
                ->once()
                ->andReturn(true);
        });

        $validator = Validator::make(['password' => 'password'], [
            'password' => Password::default(),
        ]);

        $this->assertFalse(
            $validator->passes()
        );

        $validator->setData(['password' => 'p@ssW0rd']);

        $this->assertTrue(
            $validator->passes()
        );
    }
}
