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
        Schema::create('employeeattestdays', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('employeeattest_id');

            $table->date('date');

            $table->timestamps();

            $table->foreign('employeevacation_id')->references('id')->on('employeeattests');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employeeattestdays');
    }
};
