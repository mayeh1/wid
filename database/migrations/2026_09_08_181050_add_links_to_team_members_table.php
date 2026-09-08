<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('team_members', function (Blueprint $table) {
            $table->string('portfolio_url')->nullable()->after('linkedin_url');
            $table->string('website_url')->nullable()->after('portfolio_url');
            $table->string('twitter_url')->nullable()->after('website_url');
            $table->string('facebook_url')->nullable()->after('twitter_url');
            $table->string('instagram_url')->nullable()->after('facebook_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('team_members', function (Blueprint $table) {
            $table->dropColumn(['portfolio_url', 'website_url', 'twitter_url', 'facebook_url', 'instagram_url']);
        });
    }
};
