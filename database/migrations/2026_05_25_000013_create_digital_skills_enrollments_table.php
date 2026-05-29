<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('digital_skills_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('digital_skills_item_id')->constrained('digital_skills_items')->cascadeOnDelete();
            $table->string('name');
            $table->string('email');
            $table->string('phone', 50)->nullable();
            $table->text('message')->nullable();
            $table->string('status', 50)->default('new');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('digital_skills_enrollments');
    }
};

