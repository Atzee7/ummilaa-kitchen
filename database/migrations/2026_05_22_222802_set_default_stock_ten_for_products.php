<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('products')->where('stock', 0)->update(['stock' => 10]);
    }

    public function down(): void
    {
        //
    }
};
