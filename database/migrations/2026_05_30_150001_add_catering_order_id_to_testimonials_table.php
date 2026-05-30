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
        Schema::table('testimonials', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id')->nullable()->change();
            $table->unsignedBigInteger('catering_order_id')->nullable()->after('order_id');
            $table->foreign('catering_order_id')->references('id')->on('catering_orders')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropForeign(['catering_order_id']);
            $table->dropColumn('catering_order_id');
            $table->unsignedBigInteger('order_id')->nullable(false)->change();
        });
    }
};
