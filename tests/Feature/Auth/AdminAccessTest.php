<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_non_admin_user_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin/dashboard');

        $response->assertForbidden();
    }

    public function test_admin_user_can_access_admin_dashboard(): void
    {
        $user = User::factory()->admin()->create();

        $response = $this->actingAs($user)->get('/admin/dashboard');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Dashboard')
            ->has('metrics')
            ->has('sync')
            ->has('logs')
        );
    }
}
