<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name')->default('Women in Development');
            $table->string('tagline')->nullable();
            $table->text('mission_statement')->nullable();
            $table->string('ein')->nullable();
            $table->string('founder_name')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('address')->nullable();
            $table->string('office_hours')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('google_maps_embed_url')->nullable();
            $table->string('donations_stat_label')->nullable();
            $table->unsignedInteger('women_empowered_count')->default(0);
            $table->unsignedInteger('scholarships_awarded_count')->default(0);
            $table->unsignedInteger('communities_reached_count')->default(0);
            $table->unsignedInteger('projects_completed_count')->default(0);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
