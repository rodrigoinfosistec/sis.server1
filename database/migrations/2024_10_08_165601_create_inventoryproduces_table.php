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
        Schema::create('inventoryproduces', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('inventory_id');
            $table->unsignedBigInteger('produce_id');

            $table->decimal('quantity', total: 16, places: 7)->default(0);

            $table->timestamps();

            $table->foreign('inventory_id')->references('id')->on('inventories');
            $table->foreign('produce_id')->references('id')->on('produces');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventoryproduces');
    }
};
