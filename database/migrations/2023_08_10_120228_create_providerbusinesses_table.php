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
        Schema::create('providerbusinesses', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('provider_id');

            $table->string('multiplier_type');

            $table->float('multiplier_quantity', 12, 2);
            $table->float('multiplier_value', 12, 2);

            $table->float('multiplier_ipi', 12, 2);
            $table->float('multiplier_ipi_aliquot', 12, 2);

            $table->float('margin', 12, 2);
            $table->float('shipping', 12, 2);

            $table->float('discount', 12, 2);
            $table->float('addition', 12, 2);

            $table->timestamps();

            $table->foreign('provider_id')->references('id')->on('providers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('providerbusinesses');
    }
};
