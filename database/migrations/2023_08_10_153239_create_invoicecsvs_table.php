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
        Schema::create('invoicecsvs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('invoice_id');

            $table->string('code');
            $table->string('reference')->nullable();
            $table->string('ean')->nullable();
            $table->string('name')->nullable();

            $table->float('cost', 12, 2)->nullable();
            $table->float('margin', 12, 2)->nullable();
            $table->float('value', 12, 2)->nullable();

            $table->timestamps();

            $table->foreign('invoice_id')->references('id')->on('invoices');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoicecsvs');
    }
};
