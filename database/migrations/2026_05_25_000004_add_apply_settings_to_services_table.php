<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->string('approach_image_path')->nullable()->after('screenshot_path');
            $table->string('payment_type')->default('one_time')->after('price');
            $table->json('inquiry_fields')->nullable()->after('payment_type');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['approach_image_path', 'payment_type', 'inquiry_fields']);
        });
    }
};

