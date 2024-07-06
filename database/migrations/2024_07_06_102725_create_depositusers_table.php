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
        Schema::create('depositusers', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('deposit_id');
            $table->unsignedBigInteger('user_id');

            $table->timestamps();

            $table->foreign('deposit_id')->references('id')->on('deposits');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('depositusers');
    }
};
