<?php

namespace Tests\Feature\Providers;

use App\Enums\Provider;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class SessionServiceProviderTest extends TestCase
{
    use WithFaker;

    public function testSocialiteMacro(): void
    {
        $this->assertTrue(
            Session::hasMacro('socialite')
        );

        Session::socialite(Provider::Github, $this->faker->safeEmail());

        $this->assertTrue(
            Session::has('socialite.github')
        );
    }

    public function testSocialiteMissingAllMacro(): void
    {
        $this->assertTrue(
            Session::hasMacro('socialiteMissingAll')
        );

        $this->assertTrue(
            Session::socialiteMissingAll()
        );

        Session::put('socialite.github', $this->faker->safeEmail());

        $this->assertFalse(
            Session::socialiteMissingAll()
        );
    }
}
