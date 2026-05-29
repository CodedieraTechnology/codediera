<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_inquiries', function (Blueprint $table) {
            $table->string('paystack_customer_code')->nullable()->after('access_key');
            $table->string('paystack_authorization_code')->nullable()->after('paystack_customer_code');
            $table->string('paystack_subscription_code')->nullable()->after('paystack_authorization_code');
            $table->string('paystack_email_token')->nullable()->after('paystack_subscription_code');
            $table->string('paystack_setup_reference')->nullable()->after('paystack_email_token');
        });
    }

    public function down(): void
    {
        Schema::table('service_inquiries', function (Blueprint $table) {
            $table->dropColumn([
                'paystack_customer_code',
                'paystack_authorization_code',
                'paystack_subscription_code',
                'paystack_email_token',
                'paystack_setup_reference',
            ]);
        });
    }
};

