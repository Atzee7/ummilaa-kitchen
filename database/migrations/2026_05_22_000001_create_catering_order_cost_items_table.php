<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('catering_order_cost_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('catering_order_id')->constrained()->cascadeOnDelete();
            $table->string('label');
            $table->integer('amount');
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catering_order_cost_items');
    }
};
