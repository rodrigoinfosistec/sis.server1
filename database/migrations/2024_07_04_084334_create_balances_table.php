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
        Schema::create('balances', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('provider_id');
            $table->string('provider_name');

            $table->unsignedBigInteger('deposit_id');
            $table->string('deposit_name');

            $table->unsignedBigInteger('company_id');

            $table->unsignedBigInteger('user_id');
            $table->string('user_name');

            $table->text('observation');

            $table->boolean('finished')->default(false);

            $table->timestamps();

            $table->foreign('provider_id')->references('id')->on('providers');
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
        Schema::dropIfExists('balances');
    }
};
