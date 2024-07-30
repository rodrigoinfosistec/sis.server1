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
        Schema::create('depositinputproducts', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('depositinput_id');
            $table->unsignedBigInteger('product_id');
            $table->string('product_name');

            $table->integer('identifier');

            $table->decimal('quantity', total: 16, places: 7)->default(0);
            $table->decimal('quantity_final', total: 16, places: 7)->default(0);

            $table->boolean('funded')->default(false);

            $table->timestamps();

            $table->foreign('depositinput_id')->references('id')->on('depositinputs');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('depositinputproducts');
    }
};
