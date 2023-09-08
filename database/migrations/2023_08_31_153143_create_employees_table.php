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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('company_id');
            $table->string('company_name');

            $table->string('pis')->unique();
            $table->string('name');

            $table->string('journey_start_week')->default('08:00');
            $table->string('journey_end_week')->default('17:00');
            $table->string('journey_start_saturday')->default('08:00');
            $table->string('journey_end_saturday')->default('12:00');

            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
