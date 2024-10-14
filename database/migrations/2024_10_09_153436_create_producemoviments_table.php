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
        Schema::create('producemoviments', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('produce_id');
            $table->unsignedBigInteger('deposit_id');
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('type', ['balanco', 'saida', 'entrada', 'transferencia']);

            $table->string('identification');

            $table->decimal('quantity', total: 16, places: 7)->default(0);

            $table->timestamps();

            $table->foreign('produce_id')->references('id')->on('produces');
            $table->foreign('deposit_id')->references('id')->on('deposits');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producemoviments');
    }
};
