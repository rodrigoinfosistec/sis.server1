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
        Schema::create('clockemployees', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('clock_id');

            $table->unsignedBigInteger('employee_id');
            $table->string('employee_name');

            $table->string('journey_start_week');
            $table->string('journey_end_week');
            $table->string('journey_start_saturday');
            $table->string('journey_end_saturday');

            $table->string('allowance_total')->nullable()->default(null);
            $table->string('delay_total')->nullable()->default(null);
            $table->string('extra_total')->nullable()->default(null);
            $table->string('balance_total')->nullable()->default(null);

            $table->string('note')->default('');

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
        Schema::dropIfExists('clockemployees');
    }
};
