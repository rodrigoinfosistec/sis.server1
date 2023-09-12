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
        Schema::create('clockdays', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('clock_id');
            $table->unsignedBigInteger('employee_id');

            $table->date('date');

            $table->string('input')->nullable()->default(null);
            $table->string('break_start')->nullable()->default(null);
            $table->string('break_end')->nullable()->default(null);
            $table->string('output')->nullable()->default(null);

            $table->string('journey_start')->nullable()->default(null);
            $table->string('journey_end')->nullable()->default(null);
            $table->string('journey_break')->nullable()->default(null);

            $table->string('allowance')->nullable()->default(null);
            $table->string('delay')->nullable()->default(null);
            $table->string('extra')->nullable()->default(null);
            $table->string('balance')->nullable()->default(null);

            $table->boolean('authorized')->default(false);

            $table->timestamps();

            $table->foreign('clock_id')->references('id')->on('clocks');
            $table->foreign('employee_id')->references('id')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clockdays');
    }
};
