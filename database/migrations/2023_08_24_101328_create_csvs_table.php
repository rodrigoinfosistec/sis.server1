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
        Schema::create('csvs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');

            $table->string('folder');
            $table->string('file')->unique();

            $table->unsignedBigInteger('reference_1')->nullable()->default(null);
            $table->unsignedBigInteger('reference_2')->nullable()->default(null);
            $table->unsignedBigInteger('reference_3')->nullable()->default(null);
            $table->unsignedBigInteger('reference_4')->nullable()->default(null);
            $table->unsignedBigInteger('reference_5')->nullable()->default(null);

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('csvs');
    }
};
