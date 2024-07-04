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
        Schema::create('balanceproducts', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('balance_id');
            $table->unsignedBigInteger('product_id');

            $table->decimal('quantity', total: 16, places: 7)->default(0);

            $table->timestamps();

            $table->foreign('balance_id')->references('id')->on('balances');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('balanceproducts');
    }
};
