<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        $defaults = [
            [
                'key' => 'social_media_management',
                'name' => 'Social media Management',
                'schema' => json_encode([
                    ['key' => 'brand_name', 'label' => 'Brand / Business name', 'type' => 'text', 'required' => false],
                    ['key' => 'plan', 'label' => 'Subscription plan', 'type' => 'select', 'required' => false, 'options' => ['monthly', 'yearly']],
                    ['key' => 'platforms', 'label' => 'Platforms to manage', 'type' => 'multi_select', 'required' => false, 'options' => ['facebook', 'instagram', 'x', 'tiktok', 'linkedin', 'youtube']],
                    ['key' => 'accounts', 'label' => 'Account links / handles', 'type' => 'textarea', 'required' => false],
                ]),
                'sort_order' => 10,
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'inventory',
                'name' => 'Inventory',
                'schema' => json_encode([
                    ['key' => 'business_name', 'label' => 'Business name', 'type' => 'text', 'required' => false],
                    ['key' => 'users_count', 'label' => 'Number of users', 'type' => 'number', 'required' => false],
                    ['key' => 'plan', 'label' => 'Subscription plan', 'type' => 'select', 'required' => false, 'options' => ['monthly', 'yearly']],
                    ['key' => 'need_training', 'label' => 'Need training / onboarding', 'type' => 'checkbox', 'required' => false],
                    ['key' => 'notes', 'label' => 'Notes', 'type' => 'textarea', 'required' => false],
                ]),
                'sort_order' => 20,
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'school_portal',
                'name' => 'School Portal',
                'schema' => json_encode([
                    ['key' => 'school_name', 'label' => 'School name', 'type' => 'text', 'required' => false],
                    ['key' => 'school_address', 'label' => 'School address', 'type' => 'text', 'required' => false],
                    ['key' => 'school_logo', 'label' => 'School logo', 'type' => 'image', 'required' => false],
                    ['key' => 'principal_signature', 'label' => "Principal's signature", 'type' => 'image', 'required' => false],
                    ['key' => 'collect_detailed_info', 'label' => 'Collect detailed school info', 'type' => 'checkbox', 'required' => false],
                    ['key' => 'need_mobile_app', 'label' => 'Need mobile app', 'type' => 'checkbox', 'required' => false],
                    ['key' => 'custom_scratch_card', 'label' => 'Custom scratch card', 'type' => 'checkbox', 'required' => false],
                    ['key' => 'id_card', 'label' => 'ID card', 'type' => 'checkbox', 'required' => false],
                    ['key' => 'notes', 'label' => 'School information / notes', 'type' => 'textarea', 'required' => false],
                ]),
                'sort_order' => 30,
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'payroll_attendant',
                'name' => 'Payroll / Attendant',
                'schema' => json_encode([
                    ['key' => 'company_name', 'label' => 'Company name', 'type' => 'text', 'required' => false],
                    ['key' => 'company_logo', 'label' => 'Company logo', 'type' => 'image', 'required' => false],
                    ['key' => 'company_size', 'label' => 'Company size', 'type' => 'select', 'required' => false, 'options' => ['1-10', '11-50', '51-200', '200+']],
                    ['key' => 'staff_count', 'label' => 'Number of staff', 'type' => 'number', 'required' => false],
                    ['key' => 'company_profile', 'label' => 'Company profile / notes', 'type' => 'textarea', 'required' => false],
                ]),
                'sort_order' => 40,
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'other',
                'name' => 'Others',
                'schema' => json_encode([
                    ['key' => 'details', 'label' => 'Details', 'type' => 'textarea', 'required' => false],
                ]),
                'sort_order' => 90,
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($defaults as $row) {
            $exists = DB::table('service_types')->where('key', $row['key'])->exists();
            if ($exists) continue;
            DB::table('service_types')->insert($row);
        }
    }

    public function down(): void
    {
        DB::table('service_types')->whereIn('key', [
            'social_media_management',
            'inventory',
            'school_portal',
            'payroll_attendant',
            'other',
        ])->delete();
    }
};

