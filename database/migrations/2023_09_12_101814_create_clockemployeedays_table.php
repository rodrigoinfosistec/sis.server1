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
        Schema::create('clockemployeedays', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('clockemployee_id');

            $table->string('input')->nullable()->default(null);
            $table->string('break-start')->nullable()->default(null);
            $table->string('break_end')->nullable()->default(null);
            $table->string('output')->nullable()->default(null);

            $table->string('journey_start')->nullable()->default(null);
            $table->string('journey_end')->nullable()->default(null);
            $table->string('journey_break')->nullable()->default(null);

            $table->string('delay_total')->nullable()->default(null);
            $table->string('delay_total')->nullable()->default(null);
            $table->string('extra_total')->nullable()->default(null);
            $table->string('balance_total')->nullable()->default(null);

            $table->boolean('authorized')->default(false);

            $table->timestamps();

            $table->foreign('clockemployee_id')->references('id')->on('clockemployees');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clockemployeedays');
    }
};
