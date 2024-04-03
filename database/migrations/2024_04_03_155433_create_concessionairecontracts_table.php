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
        Schema::create('concessionairecontracts', function (Blueprint $table) {
            $table->id();

            $table->string('concessionaire_name');
            $table->unsignedBigInteger('concessionaire_id');

            $table->string('company_name');
            $table->unsignedBigInteger('company_id');

            $table->string('contrato');

            $table->string('description')->nullable()->default(null);

            $table->date('start');
            $table->date('end');

            $table->integer('vencimento');

            $table->boolean('status')->default(true);

            $table->timestamps();

            $table->foreign('concessionaire_id')->references('id')->on('concessionaires');
            $table->foreign('company_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('concessionairecontracts');
    }
};
