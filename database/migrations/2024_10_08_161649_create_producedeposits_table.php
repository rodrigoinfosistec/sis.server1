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
        Schema::create('producedeposits', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('produce_id');
            $table->unsignedBigInteger('deposit_id');

            $table->decimal('quantity', total: 16, places: 7)->default(0);

            $table->timestamps();

            $table->foreign('produce_id')->references('id')->on('produces');
            $table->foreign('deposit_id')->references('id')->on('deposits');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producedeposits');
    }
};
