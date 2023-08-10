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
        Schema::create('providerbusinessdefaults', function (Blueprint $table) {
            $table->id();
            
            $table->float('multiplier_quantity', 12, 2);
            $table->float('multiplier_value', 12, 2);
            $table->float('multiplier_ipi', 12, 2);
            $table->float('multiplier_ipi_aliquot', 12, 2);

            $table->float('margin', 12, 2);
            $table->float('shipping', 12, 2);

            $table->float('discount', 12, 2);
            $table->float('addition', 12, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('providerbusinessdefaults');
    }
};
