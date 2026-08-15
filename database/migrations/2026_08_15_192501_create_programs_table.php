<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->enum('category', [
                'employment', 'entrepreneurship', 'financial_literacy', 'leadership_development',
                'mentorship', 'scholarships', 'community_development', 'emergency_assistance',
                'womens_empowerment',
            ]);
            $table->string('icon')->nullable();
            $table->text('excerpt')->nullable();
            $table->longText('description')->nullable();
            $table->longText('success_stories')->nullable();
            $table->string('apply_url')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true);
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
