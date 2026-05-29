<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('home_hero_kicker', 50)->nullable()->after('meta_description');
            $table->string('home_hero_title', 255)->nullable()->after('home_hero_kicker');
            $table->string('home_hero_body', 600)->nullable()->after('home_hero_title');

            $table->string('home_hero_item1_title', 120)->nullable()->after('home_hero_body');
            $table->string('home_hero_item1_body', 255)->nullable()->after('home_hero_item1_title');
            $table->string('home_hero_item2_title', 120)->nullable()->after('home_hero_item1_body');
            $table->string('home_hero_item2_body', 255)->nullable()->after('home_hero_item2_title');
            $table->string('home_hero_item3_title', 120)->nullable()->after('home_hero_item2_body');
            $table->string('home_hero_item3_body', 255)->nullable()->after('home_hero_item3_title');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'home_hero_kicker',
                'home_hero_title',
                'home_hero_body',
                'home_hero_item1_title',
                'home_hero_item1_body',
                'home_hero_item2_title',
                'home_hero_item2_body',
                'home_hero_item3_title',
                'home_hero_item3_body',
            ]);
        });
    }
};

