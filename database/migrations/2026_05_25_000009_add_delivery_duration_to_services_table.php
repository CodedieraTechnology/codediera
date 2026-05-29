<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->unsignedInteger('delivery_duration_value')->nullable()->after('access_code');
            $table->string('delivery_duration_unit', 20)->nullable()->after('delivery_duration_value');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['delivery_duration_value', 'delivery_duration_unit']);
        });
    }
};

