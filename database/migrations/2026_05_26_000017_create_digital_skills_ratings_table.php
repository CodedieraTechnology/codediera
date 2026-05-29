<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('digital_skills_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('digital_skills_item_id')->constrained('digital_skills_items')->cascadeOnDelete();
            $table->string('name');
            $table->string('email');
            $table->unsignedTinyInteger('rating');
            $table->text('comment')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 255)->nullable();
            $table->timestamps();

            $table->index(['digital_skills_item_id', 'rating']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('digital_skills_ratings');
    }
};
