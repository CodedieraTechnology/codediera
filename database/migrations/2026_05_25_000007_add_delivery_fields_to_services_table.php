<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->string('download_url')->nullable()->after('approach_image_path');
            $table->string('access_code')->nullable()->after('download_url');
            $table->text('instructions')->nullable()->after('access_code');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['download_url', 'access_code', 'instructions']);
        });
    }
};

