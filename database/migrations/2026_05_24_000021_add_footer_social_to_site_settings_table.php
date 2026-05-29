<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('copyright_text', 255)->nullable()->after('footer_text');
            $table->string('social_facebook', 255)->nullable()->after('copyright_text');
            $table->string('social_instagram', 255)->nullable()->after('social_facebook');
            $table->string('social_twitter', 255)->nullable()->after('social_instagram');
            $table->string('social_linkedin', 255)->nullable()->after('social_twitter');
            $table->string('social_whatsapp', 255)->nullable()->after('social_linkedin');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'copyright_text',
                'social_facebook',
                'social_instagram',
                'social_twitter',
                'social_linkedin',
                'social_whatsapp',
            ]);
        });
    }
};

