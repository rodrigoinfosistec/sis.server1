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
        Schema::create('depositoutputproducts', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('depositoutput_id');
            $table->unsignedBigInteger('product_id');
            $table->string('product_name');

            $table->decimal('quantity', total: 16, places: 7)->default(0);

            $table->timestamps();

            $table->foreign('depositoutput_id')->references('id')->on('depositoutputs');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('depositoutputproducts');
    }
};
