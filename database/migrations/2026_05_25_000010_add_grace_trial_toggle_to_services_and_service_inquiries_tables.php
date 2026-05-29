<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->boolean('grace_trial_enabled')->default(true)->after('delivery_duration_unit');
        });

        Schema::table('service_inquiries', function (Blueprint $table) {
            $table->boolean('grace_trial_enabled')->default(true)->after('progress_note');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('grace_trial_enabled');
        });

        Schema::table('service_inquiries', function (Blueprint $table) {
            $table->dropColumn('grace_trial_enabled');
        });
    }
};

