<?php

namespace Tests\Feature\Http\Controllers\Dashboard;

use App\Http\Middleware\RequirePassword;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 사용자 정보 폼 테스트
     *
     * @return void
     */
    public function testIndex()
    {
        $user = $this->user();

        $this->actingAs($user)
            ->withoutMiddleware(RequirePassword::class)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertViewIs('dashboard.user');
    }

    /**
     * User
     *
     * @return \App\Models\User
     */
    private function user()
    {
        $factory = User::factory();

        return $factory->create();
    }
}
