<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('paystack_enabled')->default(false);
            $table->string('paystack_public_key')->nullable();
            $table->text('paystack_secret_key')->nullable();
            $table->unsignedSmallInteger('trial_days')->default(3);
            $table->unsignedInteger('paystack_auth_amount_kobo')->default(10000);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_settings');
    }
};

