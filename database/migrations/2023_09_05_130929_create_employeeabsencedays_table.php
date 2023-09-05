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
        Schema::create('employeeabsencedays', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('employeeabsence_id');

            $table->date('date');

            $table->timestamps();

            $table->foreign('employeeabsence_id')->references('id')->on('employeeabsences');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employeeabsencedays');
    }
};
