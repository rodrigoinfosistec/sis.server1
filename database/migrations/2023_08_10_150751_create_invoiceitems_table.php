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
        Schema::create('invoiceitems', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('invoice_id');

            $table->boolean('equipment')->default(false);

            $table->unsignedBigInteger('productgroup_id')->nullable()->default(null);
            $table->unsignedBigInteger('invoicecsv_id')->nullable()->default(null);

            $table->string('signal')->default('divide');
            $table->float('amount', 12, 3)->default(1.000);

            $table->string('identifier');
            $table->string('code');
            $table->string('ean')->nullable();
            $table->string('name');
            $table->string('ncm');
            $table->string('cfop');
            $table->string('cest')->nullable()->defaut(null);
            $table->string('measure');

            $table->float('quantity', 12, 3);
            $table->float('quantity_final', 12, 3);
            $table->float('value', 12, 3);
            $table->float('value_final', 12, 3);

            $table->float('ipi', 12, 3)->default(0.000);
            $table->float('ipi_final', 12, 3)->default(0.000);
            $table->float('ipi_aliquot', 12, 3)->default(0.000);
            $table->float('ipi_aliquot_final', 12, 3)->default(0.000);

            $table->float('margin', 12, 2);
            $table->float('shipping', 12, 2);

            $table->float('discount', 12, 2)->default(0.00);
            $table->float('addition', 12, 2)->default(0.00);

            $table->boolean('updated')->default(false);

            $table->float('index', 12, 2)->nullable()->default(null);

            $table->float('price', 12, 2)->nullable()->default(null);
            $table->float('card', 12, 2)->nullable()->default(null);
            $table->float('retail', 12, 2)->nullable()->default(null);

            $table->timestamps();

            $table->foreign('invoice_id')->references('id')->on('invoices');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoiceitems');
    }
};
