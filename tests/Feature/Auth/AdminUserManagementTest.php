<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_users_page(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get('/admin/users');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Users/Index')
            ->has('users.data')
        );
    }

    public function test_admin_can_filter_users_by_role_and_search(): void
    {
        $admin = User::factory()->admin()->create();
        User::factory()->create(['name' => 'Alpha User', 'email' => 'alpha@example.test']);
        User::factory()->admin()->create(['name' => 'Beta Admin', 'email' => 'beta@example.test']);

        $response = $this->actingAs($admin)->get('/admin/users?search=Beta&role=admin');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Users/Index')
            ->where('filters.search', 'Beta')
            ->where('filters.role', 'admin')
            ->has('users.data', 1)
        );
    }

    public function test_admin_can_view_deleted_users_tab(): void
    {
        $admin = User::factory()->admin()->create();
        $deletedUser = User::factory()->create();
        $deletedUser->delete();

        $response = $this->actingAs($admin)->get('/admin/users?status=deleted');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Users/Index')
            ->where('filters.status', 'deleted')
            ->has('users.data', 1)
        );
    }

    public function test_admin_can_create_user(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post('/admin/users', [
            'name' => 'Editor BBM',
            'email' => 'editor@example.test',
            'password' => 'password',
            'password_confirmation' => 'password',
            'is_admin' => true,
        ]);

        $response->assertRedirect('/admin/users');
        $this->assertDatabaseHas('users', [
            'email' => 'editor@example.test',
            'is_admin' => true,
        ]);
        $this->assertDatabaseHas('admin_audit_logs', [
            'action' => 'user.created',
        ]);
    }

    public function test_admin_can_update_user(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($admin)->put("/admin/users/{$user->id}", [
            'name' => 'Updated User',
            'email' => 'updated@example.test',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
            'is_admin' => true,
        ]);

        $response->assertRedirect('/admin/users');

        $user->refresh();

        $this->assertSame('Updated User', $user->name);
        $this->assertSame('updated@example.test', $user->email);
        $this->assertTrue($user->is_admin);
        $this->assertTrue(Hash::check('new-password', $user->password));
    }

    public function test_admin_can_soft_delete_other_user(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($admin)->delete("/admin/users/{$user->id}");

        $response->assertRedirect('/admin/users');
        $this->assertSoftDeleted('users', [
            'id' => $user->id,
        ]);
    }

    public function test_admin_cannot_delete_self(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->delete("/admin/users/{$admin->id}");

        $response->assertSessionHasErrors('delete');
        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
        ]);
    }

    public function test_admin_cannot_demote_last_admin(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->patch("/admin/users/{$admin->id}/toggle-admin");

        $response->assertSessionHasErrors('role');
        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
            'is_admin' => true,
        ]);
    }

    public function test_admin_can_toggle_other_users_role(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($admin)->patch("/admin/users/{$user->id}/toggle-admin");

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'is_admin' => true,
        ]);
        $this->assertDatabaseHas('admin_audit_logs', [
            'action' => 'user.role_toggled',
        ]);
    }

    public function test_admin_can_restore_deleted_user(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();
        $user->delete();

        $response = $this->actingAs($admin)->patch("/admin/users/{$user->id}/restore");

        $response->assertRedirect('/admin/users');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'deleted_at' => null,
        ]);
        $this->assertDatabaseHas('admin_audit_logs', [
            'action' => 'user.restored',
        ]);
    }

    public function test_admin_can_view_audit_logs_page(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post('/admin/users', [
            'name' => 'Audit User',
            'email' => 'audit@example.test',
            'password' => 'password',
            'password_confirmation' => 'password',
            'is_admin' => false,
        ]);

        $response = $this->actingAs($admin)->get('/admin/audit-logs');

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Admin/AuditLogs')
            ->has('logs.data', 1)
        );
    }

    public function test_admin_cannot_toggle_self_role(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->patch("/admin/users/{$admin->id}/toggle-admin");

        $response->assertSessionHasErrors('role');
        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
            'is_admin' => true,
        ]);
    }
}
