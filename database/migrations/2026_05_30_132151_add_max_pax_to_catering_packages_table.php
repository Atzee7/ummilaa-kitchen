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
        Schema::table('catering_packages', function (Blueprint $table) {
            $table->unsignedInteger('max_pax')->nullable()->after('min_pax');
        });
    }

    public function down(): void
    {
        Schema::table('catering_packages', function (Blueprint $table) {
            $table->dropColumn('max_pax');
        });
    }
};
