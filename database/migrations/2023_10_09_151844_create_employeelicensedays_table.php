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
        Schema::create('employeelicensedays', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('employeelicense_id');
            $table->unsignedBigInteger('employee_id');

            $table->date('date');

            $table->timestamps();

            $table->foreign('employeelicense_id')->references('id')->on('employeelicenses');
            $table->foreign('employee_id')->references('id')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employeelicensedays');
    }
};
