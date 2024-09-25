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
        Schema::create('presenceinemployees', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('presencein_id');
            $table->unsignedBigInteger('employee_id');

            $table->boolean('is_present')->default(false);

            $table->timestamps();

            $table->foreign('presencein_id')->references('id')->on('presenceins');
            $table->foreign('employee_id')->references('id')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presenceinemployees');
    }
};
