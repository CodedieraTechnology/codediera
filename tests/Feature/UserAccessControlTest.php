<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserAccessControlTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles and permissions since we use RefreshDatabase
        $this->seedPermissionsAndRoles();
    }

    protected function seedPermissionsAndRoles()
    {
        $permissions = [
            'manage_users' => 'Manage Users',
            'view_dashboard' => 'View Dashboard'
        ];

        foreach ($permissions as $slug => $name) {
            Permission::firstOrCreate(['slug' => $slug], ['name' => $name]);
        }

        Role::firstOrCreate(['slug' => 'super_admin'], ['name' => 'Super Admin']);
        Role::firstOrCreate(['slug' => 'manager'], ['name' => 'Manager']);
    }

    public function test_guest_cannot_access_user_management()
    {
        $response = $this->get(route('admin.users.index'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_unauthorized_admin_user_without_permission_cannot_access_user_management()
    {
        $user = User::factory()->create(['is_admin' => true]);
        // Do not assign roles/permissions

        $response = $this->actingAs($user)->get(route('admin.users.index'));
        $response->assertStatus(403);
    }

    public function test_authorized_admin_user_with_permission_can_access_user_management()
    {
        $user = User::factory()->create(['is_admin' => true]);
        $role = Role::where('slug', 'super_admin')->first();
        
        // Super Admin gets all permissions dynamically in User model helper, or we can sync it
        $user->roles()->sync([$role->id]);

        $response = $this->actingAs($user)->get(route('admin.users.index'));
        $response->assertStatus(200);
        $response->assertSee('Manage Users');
    }

    public function test_admin_user_with_manage_users_permission_can_create_user()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $role = Role::where('slug', 'super_admin')->first();
        $admin->roles()->sync([$role->id]);

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'is_admin' => '1',
            'roles' => [$role->id]
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', ['email' => 'newuser@example.com', 'is_admin' => true]);
        
        $newUser = User::where('email', 'newuser@example.com')->first();
        $this->assertTrue($newUser->hasRole('super_admin'));
    }

    public function test_admin_user_can_create_and_manage_roles()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $superAdminRole = Role::where('slug', 'super_admin')->first();
        $admin->roles()->sync([$superAdminRole->id]);

        $permission = Permission::where('slug', 'manage_users')->first();

        // 1. Create role
        $response = $this->actingAs($admin)->post(route('admin.roles.store'), [
            'name' => 'Custom Manager',
            'description' => 'A custom role description',
            'permissions' => [$permission->id]
        ]);

        $response->assertRedirect(route('admin.roles.index'));
        $this->assertDatabaseHas('roles', ['name' => 'Custom Manager', 'slug' => 'custom_manager']);

        $role = Role::where('slug', 'custom_manager')->first();
        $this->assertTrue($role->permissions->contains($permission->id));

        // 2. Edit/Update role
        $response = $this->actingAs($admin)->put(route('admin.roles.update', $role), [
            'name' => 'Updated Manager',
            'description' => 'Updated description',
            'permissions' => []
        ]);

        $response->assertRedirect(route('admin.roles.index'));
        $this->assertDatabaseHas('roles', ['name' => 'Updated Manager', 'description' => 'Updated description']);
        
        $role->refresh();
        $this->assertCount(0, $role->permissions);

        // 3. Delete role
        $response = $this->actingAs($admin)->delete(route('admin.roles.destroy', $role));
        $response->assertRedirect(route('admin.roles.index'));
        $this->assertDatabaseMissing('roles', ['id' => $role->id]);
    }
}
