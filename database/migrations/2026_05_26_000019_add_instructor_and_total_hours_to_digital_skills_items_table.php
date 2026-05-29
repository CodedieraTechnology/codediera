<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('digital_skills_items', function (Blueprint $table) {
            $table->foreignId('instructor_user_id')->nullable()->after('image_path')->constrained('users')->nullOnDelete();
            $table->decimal('total_hours', 6, 2)->nullable()->after('instructor_user_id');
        });
    }

    public function down(): void
    {
        Schema::table('digital_skills_items', function (Blueprint $table) {
            $table->dropForeign(['instructor_user_id']);
            $table->dropColumn(['instructor_user_id', 'total_hours']);
        });
    }
};
