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
        Schema::create('employeevacationdays', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('employeevacation_id');
            $table->unsignedBigInteger('employee_id');

            $table->date('date');

            $table->timestamps();

            $table->foreign('employeevacation_id')->references('id')->on('employeevacations');
            $table->foreign('employee_id')->references('id')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employeevacationdays');
    }
};
