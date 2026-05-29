<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('it_intakes', function (Blueprint $table) {
            $table->string('email')->nullable()->after('student_name');
        });
    }

    public function down(): void
    {
        Schema::table('it_intakes', function (Blueprint $table) {
            $table->dropColumn('email');
        });
    }
};

