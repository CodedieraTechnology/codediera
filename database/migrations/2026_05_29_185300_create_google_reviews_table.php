<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('google_reviews', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('rating');
            $table->text('review_text');
            $table->string('reviewer_title')->default('Google User');
            $table->string('avatar_bg')->nullable();
            $table->string('google_review_url')->nullable();
            $table->boolean('is_approved')->default(true);
            $table->timestamps();
        });

        // Seed some initial reviews (the same beautiful ones from home page mockup)
        DB::table('google_reviews')->insert([
            [
                'name' => 'Chinedu Okafor',
                'rating' => 5,
                'review_text' => 'Codediera built our business management system. Excellent customer service, neat delivery, and their attention to details is top-notch. I highly recommend them.',
                'reviewer_title' => 'Local Guide • 5 reviews',
                'avatar_bg' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                'google_review_url' => 'https://www.google.com/search?q=Codediera+Technologies',
                'is_approved' => true,
                'created_at' => now()->subWeeks(2),
                'updated_at' => now()->subWeeks(2),
            ],
            [
                'name' => 'Amara Egwu',
                'rating' => 5,
                'review_text' => 'The training I received during my IT intake program was wonderful. The instructors are very patient and highly skilled in software engineering. 10/10!',
                'reviewer_title' => 'Local Guide • 12 reviews',
                'avatar_bg' => 'linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%)',
                'google_review_url' => 'https://www.google.com/search?q=Codediera+Technologies',
                'is_approved' => true,
                'created_at' => now()->subMonth(),
                'updated_at' => now()->subMonth(),
            ],
            [
                'name' => 'Tunde Benson',
                'rating' => 5,
                'review_text' => 'Highly professional web development team. They delivered our corporate website ahead of schedule and optimized it for search engines. Great communication throughout.',
                'reviewer_title' => 'Local Guide • 8 reviews',
                'avatar_bg' => 'linear-gradient(135deg, #f1a7a1 0%, #f77062 100%)',
                'google_review_url' => 'https://www.google.com/search?q=Codediera+Technologies',
                'is_approved' => true,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('google_reviews');
    }
};
