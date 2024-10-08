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
        Schema::create('pointevents', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('employee_id');

            $table->string('event');
            $table->date('date');
            $table->string('time');
            $table->string('code');

            $table->enum('type', ['clock', 'alternative']);

            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pointevents');
    }
};
