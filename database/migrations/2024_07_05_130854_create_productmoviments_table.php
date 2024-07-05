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
        Schema::create('productmoviments', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('product_id');

            $table->string('identification', length:50);

            $table->decimal('quantity', total: 16, places: 7)->default(0);

            $table->unsignedBigInteger('user_id');

            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productmoviments');
    }
};
