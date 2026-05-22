<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('catering_orders', function (Blueprint $table) {
            $table->dropForeign(['catering_package_id']);
            $table->unsignedBigInteger('catering_package_id')->nullable(false)->change();
            $table->foreign('catering_package_id')->references('id')->on('catering_packages')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('catering_orders', function (Blueprint $table) {
            $table->dropForeign(['catering_package_id']);
            $table->unsignedBigInteger('catering_package_id')->nullable()->change();
            $table->foreign('catering_package_id')->references('id')->on('catering_packages')->nullOnDelete();
        });
    }
};
