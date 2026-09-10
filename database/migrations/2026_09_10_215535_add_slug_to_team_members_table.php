<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('team_members', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
        });

        foreach (DB::table('team_members')->get() as $member) {
            DB::table('team_members')
                ->where('id', $member->id)
                ->update(['slug' => Str::slug($member->name).'-'.$member->id]);
        }

        Schema::table('team_members', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('team_members', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
