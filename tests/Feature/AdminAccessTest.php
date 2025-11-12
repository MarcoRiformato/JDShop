<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        Role::create(['name' => 'Admin', 'slug' => 'admin', 'description' => 'Admin role']);
        Role::create(['name' => 'Editor', 'slug' => 'editor', 'description' => 'Editor role']);
    }

    public function test_admin_has_admin_access(): void
    {
        $adminRole = Role::where('slug', 'admin')->first();
        $user = User::factory()->create(['role_id' => $adminRole->id]);

        $this->assertTrue($user->isFullAdmin());
        $this->assertTrue($user->isAdmin());
        $this->assertTrue($user->hasRole('admin'));
    }

    public function test_editor_has_admin_access(): void
    {
        $editorRole = Role::where('slug', 'editor')->first();
        $user = User::factory()->create(['role_id' => $editorRole->id]);

        $this->assertFalse($user->isFullAdmin());
        $this->assertTrue($user->isAdmin());
        $this->assertTrue($user->hasRole('editor'));
    }

    public function test_user_without_role_does_not_have_admin_access(): void
    {
        $user = User::factory()->create(['role_id' => null]);

        $this->assertFalse($user->isFullAdmin());
        $this->assertFalse($user->isAdmin());
    }

    public function test_admin_can_access_admin_routes(): void
    {
        $adminRole = Role::where('slug', 'admin')->first();
        $admin = User::factory()->create(['role_id' => $adminRole->id]);

        $response = $this->actingAs($admin)
            ->get(route('admin.dashboard'));

        $response->assertStatus(200);
    }

    public function test_non_admin_cannot_access_admin_routes(): void
    {
        $user = User::factory()->create(['role_id' => null]);

        $response = $this->actingAs($user)
            ->get(route('admin.dashboard'));

        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_admin_routes(): void
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('login'));
    }
}

