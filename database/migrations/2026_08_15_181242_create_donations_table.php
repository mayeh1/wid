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
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('donor_name')->nullable();
            $table->string('donor_email')->nullable();
            $table->boolean('anonymous')->default(false);
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->string('frequency')->default('one_time');
            $table->string('designation')->nullable();
            $table->string('provider');
            $table->string('provider_reference')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
