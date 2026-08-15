<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('type', ['gateway', 'manual'])->default('manual');
            $table->string('driver')->nullable()->comment('Driver class key for gateway-type methods, e.g. stripe, paypal');
            $table->text('config')->nullable()->comment('Gateway API keys/settings, encrypted at rest via the model cast (not native JSON — the ciphertext is not valid JSON)');
            $table->text('instructions')->nullable()->comment('Shown to donors for manual methods, e.g. bank details');
            $table->boolean('is_enabled')->default(true);
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
