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
        Schema::create('provideritems', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('code');
            $table->string('ean')->nullable();
            $table->string('ncm');
            $table->string('cfop');
            $table->string('cest')->nullable()->default(null);
            $table->string('measure');

            $table->enum('signal', ['divide', 'multiply'])->default('multiply');
            $table->decimal('amount', 12, 3)->default(1.000);

            $table->string('provider_name');
            $table->unsignedBigInteger('provider_id');

            $table->unsignedBigInteger('product_id')->nullable()->default(null);

            $table->timestamps();

            $table->foreign('provider_id')->references('id')->on('providers');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provideritems');
    }
};
