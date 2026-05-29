<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_inquiries', function (Blueprint $table) {
            $table->string('order_code')->nullable()->unique()->after('service_id');
            $table->string('access_key')->nullable()->unique()->after('order_code');
            $table->string('payment_type')->default('one_time')->after('message');
            $table->decimal('amount', 10, 2)->nullable()->after('payment_type');
            $table->string('currency', 10)->default('NGN')->after('amount');
            $table->string('payment_status')->default('pending')->after('currency');
            $table->timestamp('paid_at')->nullable()->after('payment_status');
            $table->timestamp('next_renewal_at')->nullable()->after('paid_at');
        });
    }

    public function down(): void
    {
        Schema::table('service_inquiries', function (Blueprint $table) {
            $table->dropColumn([
                'order_code',
                'access_key',
                'payment_type',
                'amount',
                'currency',
                'payment_status',
                'paid_at',
                'next_renewal_at',
            ]);
        });
    }
};

