<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::statement("ALTER TABLE products MODIFY COLUMN `type` VARCHAR(30) NOT NULL DEFAULT 'digital'");
        DB::statement("ALTER TABLE products MODIFY COLUMN `category` VARCHAR(50) NOT NULL DEFAULT 'book'");
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::table('products')
            ->whereNotIn('type', ['physical', 'digital'])
            ->update(['type' => 'digital']);

        DB::table('products')
            ->whereNotIn('category', ['book', 'magazine', 'consultancy_guide', 'toolkit', 'training_course', 'report', 'other'])
            ->update(['category' => 'other']);

        DB::statement("ALTER TABLE products MODIFY COLUMN `type` ENUM('physical','digital') NOT NULL DEFAULT 'digital'");
        DB::statement("ALTER TABLE products MODIFY COLUMN `category` ENUM('book','magazine','consultancy_guide','toolkit','training_course','report','other') NOT NULL DEFAULT 'book'");
    }
};
