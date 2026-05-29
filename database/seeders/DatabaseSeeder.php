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
            'model' => 'gemini-1.5-flash',
        ]);

        SiteSetting::query()->firstOrCreate(
            ['id' => 1],
            [
                'site_name' => config('app.name', 'Codediera'),
                'primary_color' => '#0d6efd',
                'heading_color' => '#0f172a',
            ]
        );
    }
}
