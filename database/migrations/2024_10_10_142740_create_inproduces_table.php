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
        Schema::create('inproduces', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('in_id');
            $table->unsignedBigInteger('produce_id');

            $table->decimal('quantity_old', total: 16, places: 7)->default(0);

            $table->decimal('quantity', total: 16, places: 7)->default(0);

            $table->decimal('quantity_diff', total: 16, places: 7)->default(0);

            $table->timestamps();

            $table->foreign('in_id')->references('id')->on('ins');
            $table->foreign('produce_id')->references('id')->on('produces');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inproduces');
    }
};
