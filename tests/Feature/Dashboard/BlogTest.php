<?php

namespace Tests\Feature\Dashboard;

use App\Http\Middleware\RequirePassword;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BlogTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testDashboard()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->withoutMiddleware(RequirePassword::class)
            ->get('/dashboard/blogs');

        $response->assertViewIs('dashboard.blogs');
    }
}
