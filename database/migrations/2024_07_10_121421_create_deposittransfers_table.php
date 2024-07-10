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
        Schema::create('deposittransfers', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('origin_id');
            $table->string('origin_name');

            $table->unsignedBigInteger('destiny_id');
            $table->string('destiny_name');

            $table->unsignedBigInteger('user_id');
            $table->string('user_name');

            $table->text('observation');

            $table->boolean('funded')->default(false);

            $table->timestamps();

            $table->foreign('origin_id')->references('id')->on('deposits');
            $table->foreign('destiny_id')->references('id')->on('deposits');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposittransfers');
    }
};
