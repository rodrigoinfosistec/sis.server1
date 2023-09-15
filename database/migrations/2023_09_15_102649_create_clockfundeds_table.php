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
        Schema::create('clockfundeds', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('clock_id');

            $table->date('start');
            $table->date('end');

            $table->timestamps();

            $table->foreign('clock_id')->references('id')->on('clocks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clockfundeds');
    }
};
