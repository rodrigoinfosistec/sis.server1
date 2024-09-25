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
        Schema::create('invoiceefiscos', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('productgroup_id');

            $table->float('icms', 12, 2);
            $table->float('value', 12, 2);

            $table->float('value_invoice', 12, 2)->nullable()->default(null);
            $table->float('value_final', 12, 2)->nullable()->default(null);

            $table->float('ipi_invoice', 12, 2)->nullable()->default(null);
            $table->float('ipi_final', 12, 2)->nullable()->default(null);

            $table->float('index', 12, 2)->nullable()->default(null);

            $table->timestamps();

            $table->foreign('invoice_id')->references('id')->on('invoices');
            $table->foreign('productgroup_id')->references('id')->on('productgroups');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoiceefiscos');
    }
};
