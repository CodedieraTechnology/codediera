<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('it_intakes', function (Blueprint $table) {
            $table->id();
            $table->string('student_name');
            $table->string('phone_number')->nullable();
            $table->string('matriculation_number');
            $table->string('department')->default('Computer Science');
            $table->string('level')->default('400 Level');
            $table->string('place_of_it')->default('Codediera Technologies LTD');
            $table->string('specialization')->nullable();
            $table->string('approval_status')->default('pending');
            $table->string('coordinator_signature')->nullable();
            $table->date('coordinator_date')->nullable();
            $table->timestamps();

            $table->index(['approval_status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('it_intakes');
    }
};

