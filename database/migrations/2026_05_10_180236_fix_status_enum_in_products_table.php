<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("UPDATE products SET status = 'ready' WHERE status IN ('preorder', 'pre-order')");
        DB::statement("ALTER TABLE products MODIFY COLUMN status ENUM('ready', 'habis') NOT NULL DEFAULT 'ready'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE products MODIFY COLUMN status ENUM('ready', 'preorder', 'habis') NOT NULL DEFAULT 'ready'");
    }
};