<?php

namespace Database\Seeders;

use App\Models\AiSetting;
use App\Models\ContactSetting;
use App\Models\HomeCta;
use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        HomeCta::firstOrCreate(
            ['slug' => 'apply_for_job'],
            ['heading' => 'Apply for Job', 'button_text' => 'Apply Now', 'button_url' => url('/jobs/apply')]
        );

        HomeCta::firstOrCreate(
            ['slug' => 'get_digital_skills'],
            ['heading' => 'Get Digital Skills', 'button_text' => 'Get Started', 'button_url' => url('/digital-skills')]
        );

        ContactSetting::firstOrCreate(['id' => 1], ['heading' => 'Contact Us']);

        AiSetting::query()->firstOrCreate(['id' => 1], [
            'enabled' => false,
            'provider' => 'gemini',
            'model' => 'gemini-2.0-flash',
            'ssl_verify' => true,
        ]);

        SiteSetting::query()->firstOrCreate(
            ['id' => 1],
            [
                'site_name' => config('app.name', 'Codediera'),
                'primary_color' => '#0d6efd',
                'heading_color' => '#0f172a',
            ]
        );

        \App\Models\User::query()->firstOrCreate(
            ['email' => 'admin@codediera.com'],
            [
                'name' => 'Admin User',
                'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
                'is_admin' => true,
            ]
        );

        // Seed default permissions
        $permissions = [
            [
                'name' => 'Manage Users',
                'slug' => 'manage_users',
                'description' => 'Manage administrators, instructors, roles, and permissions',
            ],
            [
                'name' => 'Manage Settings',
                'slug' => 'manage_settings',
                'description' => 'Manage site settings, mail settings, payment settings, AI settings',
            ],
            [
                'name' => 'Manage Content',
                'slug' => 'manage_content',
                'description' => 'Manage services, sliders, team members, projects, home CTAs, digital skills',
            ],
            [
                'name' => 'Manage Jobs',
                'slug' => 'manage_jobs',
                'description' => 'Manage job vacancies and applications',
            ],
            [
                'name' => 'View Dashboard',
                'slug' => 'view_dashboard',
                'description' => 'Access and view admin dashboard',
            ],
        ];

        $permissionModels = [];
        foreach ($permissions as $perm) {
            $permissionModels[$perm['slug']] = \App\Models\Permission::query()->firstOrCreate(
                ['slug' => $perm['slug']],
                $perm
            );
        }

        // Seed default roles
        $roles = [
            [
                'name' => 'Super Admin',
                'slug' => 'super_admin',
                'description' => 'Full control over the system',
            ],
            [
                'name' => 'Manager',
                'slug' => 'manager',
                'description' => 'Manages content, jobs, and applications',
            ],
            [
                'name' => 'Editor',
                'slug' => 'editor',
                'description' => 'Manages website content',
            ],
        ];

        $roleModels = [];
        foreach ($roles as $role) {
            $roleModels[$role['slug']] = \App\Models\Role::query()->firstOrCreate(
                ['slug' => $role['slug']],
                $role
            );
        }

        // Sync permissions with roles
        $roleModels['super_admin']->permissions()->sync(array_values(array_map(fn($m) => $m->id, $permissionModels)));
        
        $roleModels['manager']->permissions()->sync([
            $permissionModels['manage_content']->id,
            $permissionModels['manage_jobs']->id,
            $permissionModels['view_dashboard']->id,
        ]);

        $roleModels['editor']->permissions()->sync([
            $permissionModels['manage_content']->id,
            $permissionModels['view_dashboard']->id,
        ]);

        // Assign super_admin role to all existing admin users
        $superAdminRole = $roleModels['super_admin'];
        $admins = \App\Models\User::where('is_admin', true)->get();
        foreach ($admins as $admin) {
            $admin->roles()->syncWithoutDetaching([$superAdminRole->id]);
        }
    }
}
