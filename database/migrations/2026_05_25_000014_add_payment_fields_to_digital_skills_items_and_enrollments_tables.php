<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('digital_skills_items', function (Blueprint $table) {
            $table->boolean('is_free')->default(true)->after('description');
            $table->decimal('price', 12, 2)->nullable()->after('is_free');
            $table->string('currency', 10)->default('NGN')->after('price');
        });

        Schema::table('digital_skills_enrollments', function (Blueprint $table) {
            $table->decimal('amount', 12, 2)->nullable()->after('message');
            $table->string('currency', 10)->default('NGN')->after('amount');
            $table->string('payment_status', 50)->default('pending')->after('currency');
            $table->timestamp('paid_at')->nullable()->after('payment_status');
            $table->string('payment_reference', 80)->nullable()->after('paid_at');
        });
    }

    public function down(): void
    {
        Schema::table('digital_skills_items', function (Blueprint $table) {
            $table->dropColumn(['is_free', 'price', 'currency']);
        });

        Schema::table('digital_skills_enrollments', function (Blueprint $table) {
            $table->dropColumn(['amount', 'currency', 'payment_status', 'paid_at', 'payment_reference']);
        });
    }
};

