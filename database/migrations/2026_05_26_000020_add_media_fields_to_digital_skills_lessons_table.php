<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('digital_skills_lessons', function (Blueprint $table) {
            $table->text('brief_info')->nullable()->after('title');
            $table->string('pdf_path')->nullable()->after('video_url');
            $table->string('image_path')->nullable()->after('pdf_path');
        });
    }

    public function down(): void
    {
        Schema::table('digital_skills_lessons', function (Blueprint $table) {
            $table->dropColumn(['brief_info', 'pdf_path', 'image_path']);
        });
    }
};
