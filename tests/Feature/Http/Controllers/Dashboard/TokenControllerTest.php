<?php

namespace Tests\Feature\Http\Controllers\Dashboard;

use App\Http\Middleware\RequirePassword;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TokenControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testReturnsTokensDashboardViewForListOfToken()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->withoutMiddleware(RequirePassword::class)
            ->get(route('dashboard.tokens'))
            ->assertViewIs('dashboard.tokens');
    }
}
