<?php

namespace Tests\Feature\Permissions;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);
    }

    /** @test */
    public function admin_user_can_access_admin_route()
    {
        // Create an admin user
        $adminUser = User::factory()->create();
        $adminUser->assignRole('admin');

        // Act as the admin user
        $response = $this->actingAs($adminUser)->get('/admin');

        // Assert that the admin user can access the /admin route
        $response->assertStatus(200);
    }

    /** @test */
    public function non_admin_user_cannot_access_admin_route()
    {
        // Create a non-admin user
        $user = User::factory()->create();
        $user->assignRole('user');

        // Act as the non-admin user
        $response = $this->actingAs($user)->get('/admin');

        // Assert that the non-admin user is forbidden from accessing the /admin route
        $response->assertStatus(403);
    }

    /** @test */
    public function guest_user_cannot_access_admin_route()
    {
        // Act as a guest user (not logged in)
        $response = $this->get('/admin');

        // Assert that the guest user is forbidden from accessing the /admin route
        $response->assertStatus(403);
    }
}
