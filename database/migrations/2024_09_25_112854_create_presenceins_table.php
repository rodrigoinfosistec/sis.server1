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
        Schema::create('presenceins', function (Blueprint $table) {
            $table->id();

            $table->string('company_name');
            $table->unsignedBigInteger('company_id');

            $table->unsignedBigInteger('user_id');
            $table->string('user_name');

            $table->date('date');

            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presenceins');
    }
};
