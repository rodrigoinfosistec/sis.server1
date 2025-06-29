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
        Schema::create('breakdows', function (Blueprint $table) {
            $table->id();

            $table->string('producebrand_name');
            $table->unsignedBigInteger('producebrand_id');

            $table->unsignedBigInteger('deposit_id');
            $table->string('deposit_name');

            $table->unsignedBigInteger('producemeasure_id');
            $table->string('producemeasure_name');

            $table->unsignedBigInteger('company_id');
            $table->string('company_name');

            $table->string('list_path', 2048)->nullable();

            $table->enum('status', ['embalado', 'reembolsado', 'destinado'])->default('embalado');

            $table->timestamps();

            $table->foreign('producebrand_id')->references('id')->on('producebrands');
            $table->foreign('deposit_id')->references('id')->on('deposits');
            $table->foreign('producemeasure_id')->references('id')->on('producemeasures');
            $table->foreign('company_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('breakdows');
    }
};
