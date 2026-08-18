<?php

namespace Tests\Feature\Middleware;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class RoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Register test routes for middleware testing
        Route::middleware(['auth', 'role:admin'])->get('/test-admin', function () {
            return response('admin area');
        });

        Route::middleware(['auth', 'role:sponsor'])->get('/test-sponsor', function () {
            return response('sponsor area');
        });
    }

    public function test_admin_can_access_admin_route(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);

        $response = $this->actingAs($admin)->get('/test-admin');

        $response->assertStatus(200);
        $response->assertSee('admin area');
    }

    public function test_sponsor_cannot_access_admin_route(): void
    {
        $sponsor = User::factory()->create(['role' => UserRole::Sponsor]);

        $response = $this->actingAs($sponsor)->get('/test-admin');

        $response->assertStatus(403);
    }

    public function test_sponsor_can_access_sponsor_route(): void
    {
        $sponsor = User::factory()->create(['role' => UserRole::Sponsor]);

        $response = $this->actingAs($sponsor)->get('/test-sponsor');

        $response->assertStatus(200);
        $response->assertSee('sponsor area');
    }

    public function test_admin_cannot_access_sponsor_route(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);

        $response = $this->actingAs($admin)->get('/test-sponsor');

        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_role_protected_route(): void
    {
        $response = $this->get('/test-admin');

        // Without auth, should redirect to login
        $response->assertRedirect('/login');
    }
}
