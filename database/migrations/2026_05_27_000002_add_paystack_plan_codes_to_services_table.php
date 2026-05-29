<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->string('paystack_plan_code_monthly')->nullable()->after('payment_type');
            $table->string('paystack_plan_code_yearly')->nullable()->after('paystack_plan_code_monthly');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['paystack_plan_code_monthly', 'paystack_plan_code_yearly']);
        });
    }
};

