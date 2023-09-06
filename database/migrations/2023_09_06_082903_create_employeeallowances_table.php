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
        Schema::create('employeeallowances', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('employee_id');
            $table->string('employee_name');

            $table->date('date');

            $table->string('start');
            $table->string('end');

            $table->boolean('merged');

            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employeeallowances');
    }
};
