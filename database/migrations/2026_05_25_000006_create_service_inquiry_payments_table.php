<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_inquiry_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_inquiry_id')->constrained('service_inquiries')->cascadeOnDelete();
            $table->decimal('amount', 10, 2)->nullable();
            $table->string('currency', 10)->default('NGN');
            $table->string('status')->default('paid');
            $table->string('reference')->unique();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_inquiry_payments');
    }
};

